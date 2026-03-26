@extends('layouts.app')

@section('title', 'Request Certificate')

@section('content')

<div class="py-4" style="max-width: 42rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('my.requests') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">Request a Certificate</h2>
            <p class="small mb-0 bc-page-subtitle">Fill out the form to submit your request.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('my.requests.store') }}"
          class="card bc-card p-3 p-md-4 bc-form-panel d-flex flex-column gap-4">
        @csrf

        <!-- Resident Info (read-only) -->
        <div class="bg-light rounded p-3">
            <p class="small fw-medium text-muted mb-1">Requesting For</p>
            <p class="small fw-semibold text-dark mb-0">{{ $resident->full_name }}</p>
            <p class="small text-muted mb-0">{{ $resident->zone ?? '—' }} &middot; {{ $resident->address ?? '—' }}</p>
        </div>

        <!-- Certificate Type -->
        <div>
            <label class="form-label small fw-medium text-secondary">
                Certificate Type <span class="text-danger">*</span>
            </label>
            <select name="certificate_type" required
                    class="form-select form-select-sm {{ $errors->has('certificate_type') ? 'is-invalid' : '' }}">
                <option value="">Select certificate type...</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" {{ old('certificate_type') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('certificate_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <!-- Purpose -->
        <div>
            <label class="form-label small fw-medium text-secondary">
                Purpose <span class="text-danger">*</span>
            </label>
            <textarea name="purpose" rows="3" required
                      placeholder="State the purpose of your request (e.g., Employment, Scholarship, Loan application...)"
                      class="form-control form-control-sm {{ $errors->has('purpose') ? 'is-invalid' : '' }}">{{ old('purpose') }}</textarea>
            @error('purpose')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <!-- OR Number (optional) -->
        <div>
            <label class="form-label small fw-medium text-secondary">OR Number</label>
            <input type="text" name="or_number" value="{{ old('or_number') }}"
                   placeholder="Official Receipt Number (if applicable)"
                   class="form-control form-control-sm">
            @error('or_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <!-- Note -->
        <div class="alert alert-info py-3">
            <p class="small mb-0">
                <span class="fw-semibold">Note:</span>
                Your request will be reviewed by the Barangay Staff and approved by the Barangay Captain.
                You will be notified once your certificate is ready for release.
            </p>
        </div>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-3 pt-1">
            <button type="submit" class="btn btn-bc-primary btn-sm rounded-pill">
                Submit Request
            </button>
            <a href="{{ route('my.requests') }}" class="btn btn-outline-secondary btn-sm">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
