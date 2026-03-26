@extends('layouts.app')

@section('title', 'Add Legislation')

@section('content')

<div class="py-4" style="max-width: 48rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('legislation.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">Add Legislation</h2>
            <p class="small text-muted mb-0">Add a new ordinance or resolution.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('legislation.store') }}"
          enctype="multipart/form-data"
          class="d-flex flex-column gap-4">
        @csrf

        <!-- Basic Info -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Basic Information
            </h3>
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Type <span class="text-danger">*</span>
                    </label>
                    <select name="type" required
                            class="form-select form-select-sm {{ $errors->has('type') ? 'is-invalid' : '' }}">
                        <option value="">Select type...</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select name="status" required
                            class="form-select form-select-sm {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', 'active') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Number <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="number" value="{{ old('number') }}" required
                           placeholder="e.g. 001"
                           class="form-control form-control-sm {{ $errors->has('number') ? 'is-invalid' : '' }}">
                    @error('number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">
                        Series (Year) <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="series" value="{{ old('series', now()->year) }}" required
                           placeholder="e.g. 2025"
                           class="form-control form-control-sm {{ $errors->has('series') ? 'is-invalid' : '' }}">
                    @error('series')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Date Enacted</label>
                    <input type="date" name="date_enacted" value="{{ old('date_enacted') }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Date Effective</label>
                    <input type="date" name="date_effective" value="{{ old('date_effective') }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">
                        Title <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Full title of the ordinance or resolution"
                           class="form-control form-control-sm {{ $errors->has('title') ? 'is-invalid' : '' }}">
                    @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Description</label>
                    <textarea name="description" rows="3"
                              placeholder="Brief summary or abstract..."
                              class="form-control form-control-sm">{{ old('description') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Full Content</label>
                    <textarea name="content" rows="8"
                              placeholder="Full text of the ordinance or resolution..."
                              class="form-control form-control-sm">{{ old('content') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Tags</label>
                    <input type="text" name="tags" value="{{ old('tags') }}"
                           placeholder="e.g. environment, health, infrastructure (comma-separated)"
                           class="form-control form-control-sm">
                    <p class="small text-muted mt-1 mb-0">Separate tags with commas.</p>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Upload Document</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx" class="form-control form-control-sm">
                    <p class="small text-muted mt-1 mb-0">Max 10MB. Allowed: pdf, doc, docx.</p>
                    @error('file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-bc-primary btn-sm rounded-pill">
                Save Legislation
            </button>
            <a href="{{ route('legislation.index') }}" class="btn btn-outline-secondary btn-sm">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
