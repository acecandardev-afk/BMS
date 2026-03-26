<?php

namespace App\Http\Controllers;

use App\Models\Legislation;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LegislationController extends Controller
{
    public function index(Request $request)
    {
        $query = Legislation::with('uploader')->latest();

        // Non-staff/admin only see active
        if (!Auth::user()->hasAnyRole(['admin', 'staff'])) {
            $query->active();
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tag')) {
            $query->byTag($request->tag);
        }

        $legislations = $query->paginate(15)->withQueryString();
        $types        = Legislation::TYPES;
        $statuses     = Legislation::STATUSES;

        return view('legislation.index', compact('legislations', 'types', 'statuses'));
    }

    public function create()
    {
        $this->authorize('create', Legislation::class);
        $types    = Legislation::TYPES;
        $statuses = Legislation::STATUSES;
        return view('legislation.create', compact('types', 'statuses'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Legislation::class);

        $validated = $this->validateLegislation($request);

        if ($request->hasFile('file')) {
            $disk = config('filesystems.upload_disk');
            $validated['file_path'] = $request->file('file')->store('legislation', $disk);
        }

        $validated['uploaded_by'] = Auth::id();
        $validated['tags']        = $this->parseTags($request->tags);

        $legislation = Legislation::create($validated);

        ActivityLogService::logCreate($legislation, "Created legislation: {$legislation->full_title}");

        return redirect()->route('legislation.show', $legislation)
            ->with('success', 'The record has been added.');
    }

    public function show(Legislation $legislation)
    {
        $this->authorize('view', $legislation);
        $legislation->load('uploader');
        return view('legislation.show', compact('legislation'));
    }

    public function edit(Legislation $legislation)
    {
        $this->authorize('update', $legislation);
        $types    = Legislation::TYPES;
        $statuses = Legislation::STATUSES;
        return view('legislation.edit', compact('legislation', 'types', 'statuses'));
    }

    public function update(Request $request, Legislation $legislation)
    {
        $this->authorize('update', $legislation);

        $validated = $this->validateLegislation($request, $legislation->id);
        $oldValues = $legislation->only(array_keys($validated));

        if ($request->hasFile('file')) {
            $disk = config('filesystems.upload_disk');
            if ($legislation->file_path) {
                Storage::disk($disk)->delete($legislation->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('legislation', $disk);
        }

        $validated['tags'] = $this->parseTags($request->tags);

        $legislation->update($validated);

        ActivityLogService::logUpdate($legislation, "Updated legislation: {$legislation->full_title}", $oldValues);

        return redirect()->route('legislation.show', $legislation)
            ->with('success', 'Your changes have been saved.');
    }

    public function destroy(Legislation $legislation)
    {
        $this->authorize('delete', $legislation);

        if ($legislation->file_path) {
            Storage::disk(config('filesystems.upload_disk'))->delete($legislation->file_path);
        }

        ActivityLogService::logDelete($legislation, "Deleted legislation: {$legislation->full_title}");
        $legislation->delete();

        return redirect()->route('legislation.index')
            ->with('success', 'The record has been removed.');
    }

    private function validateLegislation(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'          => ['required', 'string', 'max:500'],
            'type'           => ['required', 'in:' . implode(',', array_keys(Legislation::TYPES))],
            'number'         => ['required', 'string', 'max:50'],
            'series'         => ['required', 'string', 'max:10'],
            'description'    => ['nullable', 'string'],
            'content'        => ['nullable', 'string'],
            'status'         => ['required', 'in:' . implode(',', array_keys(Legislation::STATUSES))],
            'date_enacted'   => ['nullable', 'date'],
            'date_effective' => ['nullable', 'date'],
            'file'           => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx'],
        ]);
    }

    private function parseTags(?string $tags): array
    {
        if (empty($tags)) return [];
        return array_values(array_filter(array_map('trim', explode(',', $tags))));
    }
}