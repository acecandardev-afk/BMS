<?php

namespace App\Http\Controllers;

use App\Models\BlotterAttachment;
use App\Models\BlotterHearing;
use App\Models\BlotterRecord;
use App\Models\Resident;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BlotterController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', BlotterRecord::class);

        $query = BlotterRecord::with(['complainant', 'respondent', 'encoder'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('blotter_number', 'like', "%{$search}%")
                  ->orWhere('complainant_name', 'like', "%{$search}%")
                  ->orWhere('respondent_name', 'like', "%{$search}%")
                  ->orWhere('narrative', 'like', "%{$search}%");
            });
        }

        $blotters       = $query->paginate(15)->withQueryString();
        $statuses       = BlotterRecord::STATUSES;
        $incident_types = BlotterRecord::INCIDENT_TYPES;

        return view('blotter.index', compact('blotters', 'statuses', 'incident_types'));
    }

    public function create()
    {
        $this->authorize('create', BlotterRecord::class);
        $residents      = Resident::orderBy('last_name')->get();
        $incident_types = BlotterRecord::INCIDENT_TYPES;
        return view('blotter.create', compact('residents', 'incident_types'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', BlotterRecord::class);

        $validated = $request->validate([
            'complainant_id'       => ['nullable', 'exists:residents,id'],
            'respondent_id'        => ['nullable', 'exists:residents,id'],
            'complainant_name'     => ['required', 'string', 'max:255'],
            'respondent_name'      => ['required', 'string', 'max:255'],
            'incident_type'        => ['required', 'in:' . implode(',', array_keys(BlotterRecord::INCIDENT_TYPES))],
            'incident_date'        => ['required', 'date'],
            'incident_location'    => ['required', 'string', 'max:255'],
            'narrative'            => ['required', 'string'],
            'assigned_to'          => ['nullable', 'exists:users,id'],
        ]);

        $validated['blotter_number'] = $this->generateBlotterNumber();
        $validated['status']         = BlotterRecord::STATUS_OPEN;
        $validated['encoded_by']     = Auth::id();

        $blotter = BlotterRecord::create($validated);

        ActivityLogService::logCreate($blotter, "Created blotter record: {$blotter->blotter_number}");

        return redirect()->route('blotter.show', $blotter)
            ->with('success', 'The blotter record has been added.');
    }

    public function show(BlotterRecord $blotter)
    {
        $this->authorize('view', $blotter);
        $blotter->load(['complainant', 'respondent', 'encoder', 'assignedOfficer', 'hearings.conductor', 'attachments.uploader']);
        return view('blotter.show', compact('blotter'));
    }

    public function edit(BlotterRecord $blotter)
    {
        $this->authorize('update', $blotter);
        $residents      = Resident::orderBy('last_name')->get();
        $incident_types = BlotterRecord::INCIDENT_TYPES;
        return view('blotter.edit', compact('blotter', 'residents', 'incident_types'));
    }

    public function update(Request $request, BlotterRecord $blotter)
    {
        $this->authorize('update', $blotter);

        $validated = $request->validate([
            'complainant_id'    => ['nullable', 'exists:residents,id'],
            'respondent_id'     => ['nullable', 'exists:residents,id'],
            'complainant_name'  => ['required', 'string', 'max:255'],
            'respondent_name'   => ['required', 'string', 'max:255'],
            'incident_type'     => ['required', 'in:' . implode(',', array_keys(BlotterRecord::INCIDENT_TYPES))],
            'incident_date'     => ['required', 'date'],
            'incident_location' => ['required', 'string', 'max:255'],
            'narrative'         => ['required', 'string'],
            'assigned_to'       => ['nullable', 'exists:users,id'],
        ]);

        $oldValues = $blotter->only(array_keys($validated));
        $blotter->update($validated);

        ActivityLogService::logUpdate($blotter, "Updated blotter record: {$blotter->blotter_number}", $oldValues);

        return redirect()->route('blotter.show', $blotter)
            ->with('success', 'Your changes have been saved.');
    }

    public function destroy(BlotterRecord $blotter)
    {
        $this->authorize('delete', $blotter);

        ActivityLogService::logDelete($blotter, "Deleted blotter record: {$blotter->blotter_number}");
        $blotter->delete();

        return redirect()->route('blotter.index')
            ->with('success', 'The record has been removed.');
    }

    public function addHearing(Request $request, BlotterRecord $blotter)
    {
        $this->authorize('addHearing', $blotter);

        $validated = $request->validate([
            'hearing_date'      => ['required', 'date'],
            'notes'             => ['required', 'string'],
            'outcome'           => ['required', 'in:' . implode(',', array_keys(BlotterHearing::OUTCOMES))],
            'next_hearing_date' => ['nullable', 'date', 'after:hearing_date'],
        ]);

        $validated['blotter_record_id'] = $blotter->id;
        $validated['conducted_by']      = Auth::id();

        $hearing = BlotterHearing::create($validated);

        // Auto-update blotter status based on outcome
        $statusMap = [
            BlotterHearing::OUTCOME_SETTLED   => BlotterRecord::STATUS_RESOLVED,
            BlotterHearing::OUTCOME_ESCALATED => BlotterRecord::STATUS_ESCALATED,
            BlotterHearing::OUTCOME_ONGOING   => BlotterRecord::STATUS_ONGOING,
            BlotterHearing::OUTCOME_NO_SHOW   => BlotterRecord::STATUS_ONGOING,
        ];

        if (isset($statusMap[$hearing->outcome])) {
            $blotter->update([
                'status'      => $statusMap[$hearing->outcome],
                'resolved_at' => $hearing->outcome === BlotterHearing::OUTCOME_SETTLED ? now() : null,
            ]);
        }

        ActivityLogService::log('update', "Added hearing to blotter #{$blotter->blotter_number}", $blotter);

        return back()->with('success', 'The hearing has been added.');
    }

    public function addAttachment(Request $request, BlotterRecord $blotter)
    {
        $this->authorize('addAttachment', $blotter);

        $request->validate([
            'file'        => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf,doc,docx'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $file     = $request->file('file');
        $disk     = config('filesystems.upload_disk');
        $path     = $file->store("blotter/{$blotter->id}/attachments", $disk);

        BlotterAttachment::create([
            'blotter_record_id' => $blotter->id,
            'uploaded_by'       => Auth::id(),
            'file_name'         => $file->getClientOriginalName(),
            'file_path'         => $path,
            'file_type'         => $file->getMimeType(),
            'file_size'         => $file->getSize(),
            'description'       => $request->description,
        ]);

        ActivityLogService::log('update', "Added attachment to blotter #{$blotter->blotter_number}", $blotter);

        return back()->with('success', 'The file has been added.');
    }

    public function resolve(Request $request, BlotterRecord $blotter)
    {
        $this->authorize('resolve', $blotter);

        $request->validate([
            'resolution' => ['required', 'string'],
        ]);

        $blotter->update([
            'status'      => BlotterRecord::STATUS_RESOLVED,
            'resolution'  => $request->resolution,
            'resolved_at' => now(),
        ]);

        ActivityLogService::log('update', "Resolved blotter record: {$blotter->blotter_number}", $blotter);

        return back()->with('success', 'The case has been marked as resolved.');
    }

    private function generateBlotterNumber(): string
    {
        $year  = now()->year;
        $count = BlotterRecord::whereYear('created_at', $year)->count() + 1;
        return sprintf('BLT-%d-%04d', $year, $count);
    }
}