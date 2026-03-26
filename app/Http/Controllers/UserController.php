<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\SupabaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()
            ->latest()
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request, SupabaseAuthService $supabase)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(User::ROLES)],
            'is_active' => ['boolean'],
        ]);

        if ($supabase->adminEnabled()) {
            $remote = $supabase->adminCreateUser($validated['email'], $validated['password'], [
                'name' => $validated['name'],
            ]);
            $validated['supabase_id'] = $remote['id'];
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        $user = User::create($validated);

        ActivityLogService::logCreate($user, "Created user: {$user->email}");

        return redirect()->route('users.index')
            ->with('success', 'The account has been added.');
    }

    public function show(User $user)
    {
        $user->load('resident', 'activityLogs');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user, SupabaseAuthService $supabase)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'      => ['required', Rule::in(User::ROLES)],
            'is_active' => ['boolean'],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $oldValues = $user->only(['name', 'email', 'role', 'is_active']);

        if (! empty($validated['password'])) {
            if ($supabase->adminEnabled() && $user->supabase_id) {
                $supabase->adminUpdateUserPassword($user->supabase_id, $validated['password']);
            }
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $user->update($validated);

        ActivityLogService::logUpdate($user, "Updated user: {$user->email}", $oldValues);

        return redirect()->route('users.index')
            ->with('success', 'Your changes have been saved.');
    }

    public function destroy(User $user)
    {
        ActivityLogService::logDelete($user, "Deleted user: {$user->email}");
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'The account has been removed.');
    }

    public function toggleActive(User $user)
    {
        $oldValues = $user->only(['is_active']);
        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        ActivityLogService::log(
            $user->is_active ? 'update' : 'update',
            "User {$status}: {$user->email}",
            $user,
            $oldValues,
            $user->only(['is_active'])
        );

        return back()->with('success', $user->is_active ? 'The account has been activated.' : 'The account has been deactivated.');
    }
}
