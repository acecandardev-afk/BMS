<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function unreadCount()
    {
        $count = ChatMessage::query()
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function index(): View
    {
        $user = Auth::user();

        $partnerIds = ChatMessage::query()
            ->where('sender_id', $user->id)
            ->pluck('receiver_id')
            ->merge(
                ChatMessage::query()
                    ->where('receiver_id', $user->id)
                    ->pluck('sender_id')
            )
            ->unique()
            ->filter();

        $partners = User::query()
            ->whereIn('id', $partnerIds)
            ->where('is_active', true)
            ->get()
            ->sortByDesc(function (User $partner) use ($user) {
                return ChatMessage::betweenUsers($user->id, $partner->id)->max('created_at');
            })
            ->values();

        $unreadCounts = [];
        foreach ($partners as $partner) {
            $unreadCounts[$partner->id] = ChatMessage::query()
                ->where('sender_id', $partner->id)
                ->where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->count();
        }

        $lastMessages = [];
        foreach ($partners as $partner) {
            $lastMessages[$partner->id] = ChatMessage::betweenUsers($user->id, $partner->id)
                ->latest('created_at')
                ->first();
        }

        return view('chat.index', compact('partners', 'unreadCounts', 'lastMessages'));
    }

    public function create(Request $request): View
    {
        $user = Auth::user();

        $query = User::query()
            ->where('id', '!=', $user->id)
            ->where('is_active', true);

        if ($user->isOfficeUser()) {
            // office can message anyone
        } else {
            $query->whereIn('role', [User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_SIGNATORY]);
        }

        if ($request->filled('search')) {
            $s = '%'.$request->input('search').'%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)->orWhere('email', 'like', $s);
            });
        }

        $users = $query->orderBy('name')->limit(100)->get();

        return view('chat.create', compact('users'));
    }

    public function show(User $user): View
    {
        $auth = Auth::user();

        if (! $auth->canCommunicateWith($user) || ! $user->is_active) {
            abort(403);
        }

        ChatMessage::query()
            ->where('sender_id', $user->id)
            ->where('receiver_id', $auth->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::betweenUsers($auth->id, $user->id)
            ->with(['sender:id,name'])
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $messagesPayload = $messages->map(fn (ChatMessage $m) => $this->messagePayload($m))->values();
        $lastMessageId = (int) $messages->max('id');

        return view('chat.show', [
            'partner' => $user,
            'messages' => $messages,
            'messagesPayload' => $messagesPayload,
            'lastMessageId' => $lastMessageId,
        ]);
    }

    public function store(Request $request, User $user)
    {
        $auth = Auth::user();

        if (! $auth->canChatWith($user) || ! $user->is_active) {
            return redirect()
                ->route('messages.index')
                ->with('error', 'You are not able to send a message to that person. Please choose someone from your contacts list.');
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ], [
            'body.required' => 'Please type a message before sending.',
            'body.max' => 'Your message is too long. Please shorten it and try again.',
        ]);

        ChatMessage::create([
            'sender_id' => $auth->id,
            'receiver_id' => $user->id,
            'body' => trim($validated['body']),
        ]);

        return redirect()
            ->route('messages.show', $user)
            ->with('success', 'Your message was sent.');
    }

    public function sync(User $user, Request $request)
    {
        $auth = Auth::user();

        if (! $auth->canCommunicateWith($user) || ! $user->is_active) {
            return response()->json(['messages' => []], 403);
        }

        $afterId = max(0, (int) $request->query('after', 0));

        $messages = ChatMessage::betweenUsers($auth->id, $user->id)
            ->where('id', '>', $afterId)
            ->with(['sender:id,name'])
            ->orderBy('id')
            ->get();

        $receivedIds = $messages->where('receiver_id', $auth->id)->pluck('id');
        if ($receivedIds->isNotEmpty()) {
            ChatMessage::query()->whereIn('id', $receivedIds)->update(['read_at' => now()]);
        }

        return response()->json([
            'messages' => $messages->map(fn (ChatMessage $m) => $this->messagePayload($m))->values(),
        ]);
    }

    private function messagePayload(ChatMessage $m): array
    {
        return [
            'id' => $m->id,
            'body' => $m->body,
            'mine' => $m->sender_id === Auth::id(),
            'sender_name' => $m->sender->name,
            'time' => $m->created_at->format('g:i A'),
            'date' => $m->created_at->format('M j, Y'),
        ];
    }
}
