@extends('layouts.app')

@section('title', 'Blotter Details')

@section('content')
<div class="py-4" style="max-width: 70rem;">
    <div class="d-flex align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('blotter.index') }}" class="bc-back-link text-decoration-none">&larr;</a>
            <div>
                <h2 class="h5 fw-semibold mb-0">{{ $blotter->blotter_number }}</h2>
                <p class="small text-muted mb-0">{{ $blotter->incident_type_name }} &middot; {{ $blotter->incident_date?->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <x-badge :status="$blotter->status" />
            @can('update', $blotter)
                <a href="{{ route('blotter.edit', $blotter) }}" class="btn btn-outline-primary btn-sm rounded-pill">Edit</a>
            @endcan
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card bc-card p-3 p-md-4 bc-form-panel mb-4">
                <h3 class="bc-form-section-title">Incident Details</h3>
                <div class="row g-3 small">
                    <div class="col-6"><span class="text-muted">Complainant</span><div>{{ $blotter->complainant_name }}</div></div>
                    <div class="col-6"><span class="text-muted">Respondent</span><div>{{ $blotter->respondent_name }}</div></div>
                    <div class="col-6"><span class="text-muted">Location</span><div>{{ $blotter->incident_location }}</div></div>
                    <div class="col-6"><span class="text-muted">Encoded By</span><div>{{ $blotter->encoder?->name ?? '—' }}</div></div>
                    <div class="col-12">
                        <span class="text-muted">Narrative</span>
                        <p class="mb-0 mt-1">{{ $blotter->narrative }}</p>
                    </div>
                    @if($blotter->resolution)
                        <div class="col-12">
                            <span class="text-muted">Resolution</span>
                            <p class="mb-0 mt-1">{{ $blotter->resolution }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card bc-card p-3 p-md-4 bc-form-panel mb-4">
                <h3 class="bc-form-section-title">Hearings</h3>
                @forelse($blotter->hearings as $hearing)
                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between small">
                            <strong>{{ $hearing->hearing_date?->format('M d, Y h:i A') }}</strong>
                            <x-badge :status="$hearing->outcome" :label="$hearing->outcome_name" />
                        </div>
                        <p class="small mb-1 mt-2">{{ $hearing->notes }}</p>
                        <p class="small text-muted mb-0">Conducted by {{ $hearing->conductor?->name ?? '—' }}</p>
                    </div>
                @empty
                    <p class="small text-muted mb-0">No hearings recorded.</p>
                @endforelse
            </div>

            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="bc-form-section-title">Attachments</h3>
                @forelse($blotter->attachments as $attachment)
                    <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2 small">
                        <div>
                            <div class="fw-medium">{{ $attachment->file_name }}</div>
                            <div class="text-muted">{{ $attachment->file_size_formatted }}</div>
                        </div>
                        <a href="{{ $attachment->file_url }}" target="_blank" class="btn btn-outline-secondary btn-sm">Open</a>
                    </div>
                @empty
                    <p class="small text-muted mb-0">No attachments uploaded.</p>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-lg-5">
            @can('addHearing', $blotter)
            <div class="card bc-card p-3 p-md-4 bc-form-panel mb-4">
                <h3 class="bc-form-section-title">Add Hearing</h3>
                <form method="POST" action="{{ route('blotter.hearings.store', $blotter) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small">Hearing Date</label>
                        <input type="datetime-local" name="hearing_date" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Outcome</label>
                        <select name="outcome" class="form-select form-select-sm" required>
                            @foreach(\App\Models\BlotterHearing::OUTCOMES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Notes</label>
                        <textarea name="notes" rows="3" class="form-control form-control-sm" required></textarea>
                    </div>
                    <button class="btn btn-bc-primary btn-sm rounded-pill" type="submit">Save Hearing</button>
                </form>
            </div>
            @endcan

            @can('addAttachment', $blotter)
            <div class="card bc-card p-3 p-md-4 bc-form-panel mb-4">
                <h3 class="bc-form-section-title">Upload Attachment</h3>
                <form method="POST" action="{{ route('blotter.attachments.store', $blotter) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small">File</label>
                        <input type="file" name="file" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Description</label>
                        <input type="text" name="description" class="form-control form-control-sm">
                    </div>
                    <button class="btn btn-outline-primary btn-sm rounded-pill" type="submit">Upload</button>
                </form>
            </div>
            @endcan

            @can('resolve', $blotter)
            @if(!$blotter->is_resolved)
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="bc-form-section-title">Resolve Case</h3>
                <form method="POST" action="{{ route('blotter.resolve', $blotter) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-2">
                        <label class="form-label small">Resolution Notes</label>
                        <textarea name="resolution" rows="3" class="form-control form-control-sm" required></textarea>
                    </div>
                    <button class="btn btn-success btn-sm rounded-pill" type="submit">Mark as Resolved</button>
                </form>
            </div>
            @endif
            @endcan
        </div>
    </div>
</div>
@endsection
