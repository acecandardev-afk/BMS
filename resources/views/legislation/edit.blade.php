@extends('layouts.app')

@section('title', 'Edit Legislation')

@section('content')

<div class="py-4" style="max-width: 48rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('legislation.show', $legislation) }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">Edit Legislation</h2>
            <p class="small text-muted mb-0">{{ $legislation->full_title }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('legislation.update', $legislation) }}"
          enctype="multipart/form-data"
          class="d-flex flex-column gap-4">
        @csrf
        @method('PUT')

        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Basic Information
            </h3>
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Type <span class="text-danger">*</span></label>
                    <select name="type" required
                            class="form-select form-select-sm {{ $errors->has('type') ? 'is-invalid' : '' }}">
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $legislation->type) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Status <span class="text-danger">*</span></label>
                    <select name="status" required
                            class="form-select form-select-sm {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $legislation->status) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Number <span class="text-danger">*</span></label>
                    <input type="text" name="number" value="{{ old('number', $legislation->number) }}" required
                           class="form-control form-control-sm {{ $errors->has('number') ? 'is-invalid' : '' }}">
                    @error('number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Series (Year) <span class="text-danger">*</span></label>
                    <input type="text" name="series" value="{{ old('series', $legislation->series) }}" required
                           class="form-control form-control-sm {{ $errors->has('series') ? 'is-invalid' : '' }}">
                    @error('series')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Date Enacted</label>
                    <input type="date" name="date_enacted"
                           value="{{ old('date_enacted', $legislation->date_enacted?->format('Y-m-d')) }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12 col-sm-6">
                    <label class="form-label small fw-medium text-secondary">Date Effective</label>
                    <input type="date" name="date_effective"
                           value="{{ old('date_effective', $legislation->date_effective?->format('Y-m-d')) }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $legislation->title) }}" required
                           class="form-control form-control-sm {{ $errors->has('title') ? 'is-invalid' : '' }}">
                    @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Description</label>
                    <textarea name="description" rows="3"
                              class="form-control form-control-sm">{{ old('description', $legislation->description) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Full Content</label>
                    <textarea name="content" rows="8"
                              class="form-control form-control-sm">{{ old('content', $legislation->content) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Tags</label>
                    <input type="text" name="tags"
                           value="{{ old('tags', $legislation->tags ? implode(', ', $legislation->tags) : '') }}"
                           placeholder="e.g. environment, health (comma-separated)"
                           class="form-control form-control-sm">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-medium text-secondary">Replace Document</label>
                    @if($legislation->file_path)
                    <div class="mb-2 d-flex align-items-center gap-3">
                        <a href="{{ $legislation->file_url }}" target="_blank"
                           class="small text-primary text-decoration-none">
                            View current document
                        </a>
                        <span class="small text-muted">(Upload a new file to replace)</span>
                    </div>
                    @endif
                    <input type="file" name="file" accept=".pdf,.doc,.docx" class="form-control form-control-sm">
                    <p class="small text-muted mt-1 mb-0">Max 10MB. Allowed: pdf, doc, docx.</p>
                    @error('file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-bc-primary btn-sm rounded-pill">
                Save Changes
            </button>
            <a href="{{ route('legislation.show', $legislation) }}" class="btn btn-outline-secondary btn-sm">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
