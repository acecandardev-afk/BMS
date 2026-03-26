<?php

namespace App\Http\Controllers;

use App\Models\BarangayEvent;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangayEventController extends Controller
{
    public function index(): View
    {
        $events = BarangayEvent::with('author')
            ->latest()
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    public function show(BarangayEvent $event): View
    {
        $event->load('author');

        return view('events.show', compact('event'));
    }

    public function create(): View
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string', 'max:20000'],
        ]);

        $validated['user_id'] = $request->user()->id;

        $event = BarangayEvent::create($validated);

        ActivityLogService::logCreate($event, "Posted barangay update: {$event->title}");

        return redirect()->route('events.index')
            ->with('success', 'The update has been published.');
    }

    public function edit(BarangayEvent $event): View
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, BarangayEvent $event)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string', 'max:20000'],
        ]);

        $old = $event->only(['title', 'body']);

        $event->update($validated);

        ActivityLogService::logUpdate($event, "Updated barangay update: {$event->title}", $old);

        return redirect()->route('events.index')
            ->with('success', 'The update has been saved.');
    }

    public function destroy(BarangayEvent $event)
    {
        $title = $event->title;
        ActivityLogService::logDelete($event, "Removed barangay update: {$title}");
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'The update has been removed.');
    }
}
