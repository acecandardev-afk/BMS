@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="py-4" style="max-width: 48rem;">

    <div class="mb-4">
        <h2 class="h5 mb-0 bc-page-title">My Profile</h2>
        <p class="small text-muted mb-0">Update your personal details and contact information for barangay records and certificates.</p>
    </div>

    <div class="row g-4">
        <!-- Profile Summary -->
        <div class="col-12 col-lg-4">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <div class="d-flex flex-column align-items-center text-center mb-4">
                    @if($resident->photo)
                        <img src="{{ $resident->photo_url }}"
                             class="rounded-circle object-fit-cover border border-primary border-2 mb-3" style="width: 5rem; height: 5rem;">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center mb-3" style="width: 5rem; height: 5rem;">
                            <span class="text-primary fs-5 fw-bold">
                                @php
                                    $a = strtoupper(substr($resident->first_name ?? '', 0, 1));
                                    $b = strtoupper(substr($resident->last_name ?? '', 0, 1));
                                @endphp
                                {{ $b !== '' ? $a.$b : $a }}
                            </span>
                        </div>
                    @endif
                    <h3 class="fw-semibold text-dark mb-0">{{ $resident->full_name }}</h3>
                    <p class="small text-muted mb-0 mt-1">{{ $resident->zone ?? '—' }}</p>
                    <div class="mt-2 d-flex flex-wrap justify-content-center gap-1">
                        @if($resident->voter_status)
                            <x-badge status="approved" label="Voter"/>
                        @endif
                        @if($resident->is_pwd)
                            <x-badge status="ongoing" label="PWD"/>
                        @endif
                        @if($resident->is_4ps)
                            <x-badge status="pending" label="4Ps"/>
                        @endif
                        @if($resident->is_solo_parent)
                            <x-badge status="released" label="Solo Parent"/>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Age</span>
                        <span class="fw-medium text-dark">{{ $resident->age ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Gender</span>
                        <span class="fw-medium text-dark text-capitalize">{{ $resident->gender ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Civil Status</span>
                        <span class="fw-medium text-dark text-capitalize">{{ $resident->civil_status ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Birthdate</span>
                        <span class="fw-medium text-dark">{{ $resident->birthdate?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Nationality</span>
                        <span class="fw-medium text-dark">{{ $resident->nationality ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Editable Info -->
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column gap-4">
                <form method="POST" action="{{ route('my.profile.update') }}"
                      class="card bc-card p-3 p-md-4 bc-form-panel">
                    @csrf
                    @method('PUT')

                    <h3 class="bc-form-section-title">Personal information</h3>
                    <p class="small text-muted mb-3">Age is calculated from your date of birth. Use the same details that should appear on certificates.</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-medium text-secondary">Date of birth</label>
                            <input type="date" name="birthdate"
                                   value="{{ old('birthdate', $resident->birthdate?->format('Y-m-d')) }}"
                                   max="{{ now()->subDay()->format('Y-m-d') }}"
                                   class="form-control form-control-sm @error('birthdate') is-invalid @enderror">
                            @error('birthdate')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-medium text-secondary">Place of birth</label>
                            <input type="text" name="birthplace"
                                   value="{{ old('birthplace', $resident->birthplace) }}"
                                   placeholder="City or municipality"
                                   class="form-control form-control-sm @error('birthplace') is-invalid @enderror">
                            @error('birthplace')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-medium text-secondary">Gender</label>
                            <select name="gender" class="form-select form-select-sm @error('gender') is-invalid @enderror">
                                <option value="">— Select —</option>
                                <option value="male" {{ old('gender', $resident->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $resident->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-medium text-secondary">Civil status</label>
                            <select name="civil_status" class="form-select form-select-sm @error('civil_status') is-invalid @enderror">
                                <option value="">— Select —</option>
                                @foreach(['single' => 'Single', 'married' => 'Married', 'widowed' => 'Widowed', 'separated' => 'Separated', 'annulled' => 'Annulled'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('civil_status', $resident->civil_status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('civil_status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-medium text-secondary">Nationality</label>
                            <input type="text" name="nationality"
                                   value="{{ old('nationality', $resident->nationality ?? 'Filipino') }}"
                                   class="form-control form-control-sm @error('nationality') is-invalid @enderror">
                            @error('nationality')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-medium text-secondary">Zone / purok</label>
                            <input type="text" name="zone"
                                   value="{{ old('zone', $resident->zone) }}"
                                   placeholder="e.g. Purok 1"
                                   class="form-control form-control-sm @error('zone') is-invalid @enderror">
                            @error('zone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <h3 class="bc-form-section-title">Contact information</h3>

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-medium text-secondary">Contact Number</label>
                            <input type="text" name="contact_number"
                                   value="{{ old('contact_number', $resident->contact_number) }}"
                                   class="form-control form-control-sm">
                            @error('contact_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label class="form-label small fw-medium text-secondary">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $resident->email) }}"
                                   class="form-control form-control-sm">
                            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-medium text-secondary">Address</label>
                            <input type="text" name="address"
                                   value="{{ old('address', $resident->address) }}"
                                   class="form-control form-control-sm">
                            @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-medium text-secondary">Occupation</label>
                            <input type="text" name="occupation"
                                   value="{{ old('occupation', $resident->occupation) }}"
                                   class="form-control form-control-sm">
                            @error('occupation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-bc-primary btn-sm rounded-pill">
                            Save Changes
                        </button>
                    </div>

                </form>

                <!-- My Certificate Requests -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="small fw-semibold text-secondary mb-0">My Certificate Requests</h3>
                        <a href="{{ route('my.requests') }}"
                           class="small text-primary text-decoration-none">View all</a>
                    </div>
                    @forelse($resident->certificateRequests->take(5) as $req)
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-secondary border-opacity-10">
                        <div>
                            <p class="small fw-medium text-dark mb-0">{{ $req->type_name }}</p>
                            <p class="small text-muted mb-0">
                                {{ $req->created_at->format('M d, Y') }} &middot; {{ $req->purpose }}
                            </p>
                        </div>
                        <x-badge :status="$req->status"/>
                    </div>
                    @empty
                    <p class="small text-muted mb-0">No requests yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
