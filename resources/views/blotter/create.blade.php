@extends('layouts.app')

@section('title', 'File Blotter')

@section('content')

<div class="py-4" style="max-width: 56rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('blotter.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">File Blotter Record</h2>
            <p class="small text-muted mb-0">Record a new dispute or incident.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('blotter.store') }}"
          class="d-flex flex-column gap-4">
        @csrf

        <!-- Parties Involved -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Parties Involved
            </h3>
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Complainant Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="complainant_name" value="{{ old('complainant_name') }}" required
                           class="form-control form-control-sm {{ $errors->has('complainant_name') ? 'is-invalid' : '' }}">
                    @error('complainant_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Link to Resident (Complainant)
                    </label>
                    <select name="complainant_id" class="form-select form-select-sm">
                        <option value="">Not a registered resident</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}" {{ old('complainant_id') == $resident->id ? 'selected' : '' }}>
                                {{ $resident->full_name }} — {{ $resident->zone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Respondent Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="respondent_name" value="{{ old('respondent_name') }}" required
                           class="form-control form-control-sm {{ $errors->has('respondent_name') ? 'is-invalid' : '' }}">
                    @error('respondent_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Link to Resident (Respondent)
                    </label>
                    <select name="respondent_id" class="form-select form-select-sm">
                        <option value="">Not a registered resident</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}" {{ old('respondent_id') == $resident->id ? 'selected' : '' }}>
                                {{ $resident->full_name }} — {{ $resident->zone }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <!-- Incident Details -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Incident Details
            </h3>
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Incident Type <span class="text-danger">*</span>
                    </label>
                    <select name="incident_type" required
                            class="form-select form-select-sm {{ $errors->has('incident_type') ? 'is-invalid' : '' }}">
                        <option value="">Select type...</option>
                        @foreach($incident_types as $key => $label)
                            <option value="{{ $key }}" {{ old('incident_type') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('incident_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Incident Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="incident_date" value="{{ old('incident_date') }}" required
                           class="form-control form-control-sm {{ $errors->has('incident_date') ? 'is-invalid' : '' }}">
                    @error('incident_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">
                        Incident Location <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="incident_location" value="{{ old('incident_location') }}" required
                           placeholder="Where did the incident occur?"
                           class="form-control form-control-sm {{ $errors->has('incident_location') ? 'is-invalid' : '' }}">
                    @error('incident_location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">
                        Narrative <span class="text-danger">*</span>
                    </label>
                    <textarea name="narrative" rows="5" required
                              placeholder="Describe the incident in detail..."
                              class="form-control form-control-sm {{ $errors->has('narrative') ? 'is-invalid' : '' }}">{{ old('narrative') }}</textarea>
                    @error('narrative')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Assigned Officer</label>
                    <select name="assigned_to" class="form-select form-select-sm">
                        <option value="">Not yet assigned</option>
                        @foreach(\App\Models\User::whereIn('role', ['admin', 'staff'])->get() as $officer)
                            <option value="{{ $officer->id }}" {{ old('assigned_to') == $officer->id ? 'selected' : '' }}>
                                {{ $officer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-bc-primary btn-sm rounded-pill">
                File Blotter
            </button>
            <a href="{{ route('blotter.index') }}" class="btn btn-outline-secondary btn-sm">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
