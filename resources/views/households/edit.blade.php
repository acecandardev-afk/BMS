@extends('layouts.app')

@section('title', 'Edit Household')

@section('content')
<div class="py-4" style="max-width: 56rem;">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('households.show', $household) }}" class="bc-back-link text-decoration-none">&larr;</a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">Edit Household</h2>
            <p class="small mb-0 bc-page-subtitle">{{ $household->household_number }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('households.update', $household) }}" class="card bc-card p-3 p-md-4 bc-form-panel">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label small fw-medium">Household Number</label>
                <input type="text" name="household_number" value="{{ old('household_number', $household->household_number) }}" class="form-control @error('household_number') is-invalid @enderror" required>
                @error('household_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label small fw-medium">Zone</label>
                <input type="text" name="zone" value="{{ old('zone', $household->zone) }}" class="form-control @error('zone') is-invalid @enderror" required>
                @error('zone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label small fw-medium">Address</label>
                <input type="text" name="address" value="{{ old('address', $household->address) }}" class="form-control @error('address') is-invalid @enderror" required>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label small fw-medium">Head of Household</label>
                <select name="head_resident_id" class="form-select @error('head_resident_id') is-invalid @enderror">
                    <option value="">Not assigned</option>
                    @foreach($residents as $resident)
                        <option value="{{ $resident->id }}" @selected(old('head_resident_id', $household->head_resident_id) == $resident->id)>
                            {{ $resident->full_name }} — {{ $resident->zone }}
                        </option>
                    @endforeach
                </select>
                @error('head_resident_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label small fw-medium">Remarks</label>
                <textarea name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $household->remarks) }}</textarea>
                @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-bc-primary rounded-pill">Save Changes</button>
            <a href="{{ route('households.show', $household) }}" class="btn btn-outline-secondary rounded-pill">Cancel</a>
        </div>
    </form>
</div>
@endsection
