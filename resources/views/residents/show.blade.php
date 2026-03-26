@extends('layouts.app')

@section('title', 'Resident Profile')

@section('content')

<div class="py-4" style="max-width: 64rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('residents.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-grow-1">
            <h2 class="h5 mb-0 bc-page-title">Resident Profile</h2>
            <p class="small text-muted mb-0">{{ $resident->full_name }}</p>
        </div>
        @can('update', $resident)
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('residents.edit', $resident) }}"
               class="btn btn-bc-primary btn-sm rounded-pill">
                Edit Profile
            </a>
            @can('delete', $resident)
            <form method="POST" action="{{ route('residents.destroy', $resident) }}"
                  data-bc-confirm="Delete this resident record?">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    Delete
                </button>
            </form>
            @endcan
        </div>
        @endcan
    </div>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-12 col-lg-4">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <!-- Photo -->
                <div class="d-flex flex-column align-items-center text-center mb-4">
                    @if($resident->photo)
                        <img src="{{ $resident->photo_url }}"
                             class="rounded-circle object-fit-cover border border-primary border-2 mb-3" style="width: 6rem; height: 6rem;">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center mb-3" style="width: 6rem; height: 6rem;">
                            <span class="text-primary fs-4 fw-bold">
                                {{ strtoupper(substr($resident->first_name, 0, 1) . substr($resident->last_name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    <h3 class="fw-semibold text-dark mb-0">{{ $resident->full_name }}</h3>
                    <p class="small text-muted mb-0">{{ $resident->zone }}</p>
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
                        @if($resident->is_indigenous)
                            <x-badge status="draft" label="Indigenous"/>
                        @endif
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Age</span>
                        <span class="fw-medium text-dark">{{ $resident->age !== null ? $resident->age.' years old' : '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Gender</span>
                        <span class="fw-medium text-dark text-capitalize">{{ $resident->gender }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Civil Status</span>
                        <span class="fw-medium text-dark text-capitalize">{{ $resident->civil_status }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Birthdate</span>
                        <span class="fw-medium text-dark">{{ $resident->birthdate?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Birthplace</span>
                        <span class="fw-medium text-dark">{{ $resident->birthplace ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Nationality</span>
                        <span class="fw-medium text-dark">{{ $resident->nationality ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Religion</span>
                        <span class="fw-medium text-dark">{{ $resident->religion ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Occupation</span>
                        <span class="fw-medium text-dark">{{ $resident->occupation ?? '—' }}</span>
                    </div>
                    @if($resident->household)
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Household</span>
                        <a href="{{ route('households.show', $resident->household) }}"
                           class="text-primary text-decoration-none fw-medium">
                            HH# {{ $resident->household->household_number }}
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Contact -->
                <div class="mt-4 pt-4 border-top small">
                    <div class="d-flex align-items-center gap-2 text-secondary mb-2">
                        <svg style="width: 1rem; height: 1rem; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $resident->contact_number ?? 'No contact number' }}
                    </div>
                    <div class="d-flex align-items-center gap-2 text-secondary mb-2">
                        <svg style="width: 1rem; height: 1rem; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $resident->email ?? 'No email' }}
                    </div>
                    <div class="d-flex align-items-start gap-2 text-secondary">
                        <svg style="width: 1rem; height: 1rem; flex-shrink: 0; margin-top: 0.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $resident->address }}
                    </div>
                </div>

                @if($resident->remarks)
                <div class="mt-4 pt-4 border-top">
                    <p class="small fw-medium text-muted mb-1">Remarks</p>
                    <p class="small text-secondary">{{ $resident->remarks }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column gap-4">
                <!-- Certificate Requests -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="small fw-semibold text-secondary mb-0">Certificate Requests</h3>
                        @can('manage-certificates')
                        <a href="{{ route('certificate-requests.index') }}?resident_id={{ $resident->id }}"
                           class="small text-primary text-decoration-none">View all</a>
                        @endcan
                    </div>
                    @forelse($resident->certificateRequests->take(5) as $req)
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-secondary border-opacity-10">
                        <div>
                            <p class="small fw-medium text-dark mb-0">{{ $req->type_name }}</p>
                            <p class="small text-muted mb-0">{{ $req->created_at->format('M d, Y') }} &middot; {{ $req->purpose }}</p>
                        </div>
                        <x-badge :status="$req->status"/>
                    </div>
                    @empty
                    <p class="small text-muted mb-0">No certificate requests yet.</p>
                    @endforelse
                </div>

                <!-- Blotter Records -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="small fw-semibold text-secondary mb-0">Blotter Records</h3>
                        @can('manage-blotter')
                        <a href="{{ route('blotter.index') }}"
                           class="small text-primary text-decoration-none">View all</a>
                        @endcan
                    </div>
                    @forelse($blotterRecords->take(5) as $blotter)
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-secondary border-opacity-10">
                        <div>
                            <p class="small fw-medium text-dark mb-0">{{ $blotter->blotter_number }}</p>
                            <p class="small text-muted mb-0">
                                {{ $blotter->incident_type_name }} &middot; {{ $blotter->incident_date->format('M d, Y') }}
                            </p>
                        </div>
                        <x-badge :status="$blotter->status"/>
                    </div>
                    @empty
                    <p class="small text-muted mb-0">No blotter records.</p>
                    @endforelse
                </div>

                <!-- Linked Account -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="small fw-semibold text-secondary mb-3">Linked System Account</h3>
                    @if($resident->user)
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="small fw-medium text-dark mb-0">{{ $resident->user->name }}</p>
                            <p class="small text-muted mb-0">{{ $resident->user->email }}</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <x-badge :status="$resident->user->role"/>
                            @can('manage-users')
                            <a href="{{ route('users.show', $resident->user) }}"
                               class="small text-primary text-decoration-none">View</a>
                            @endcan
                        </div>
                    </div>
                    @else
                    <p class="small text-muted mb-0">No system account linked.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
