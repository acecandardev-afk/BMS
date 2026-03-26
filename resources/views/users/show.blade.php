@extends('layouts.app')

@section('title', 'User Detail')

@section('content')

<div class="py-4" style="max-width: 56rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('users.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-grow-1">
            <h2 class="h5 mb-0 bc-page-title">User Detail</h2>
            <p class="small text-muted mb-0">{{ $user->email }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-bc-primary btn-sm rounded-pill">
                Edit
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Card -->
        <div class="col-12 col-lg-4">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <div class="d-flex flex-column align-items-center text-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center mb-3" style="width: 4rem; height: 4rem;">
                        <span class="text-primary fs-5 fw-bold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                    <h3 class="fw-semibold text-dark mb-0">{{ $user->name }}</h3>
                    <p class="small text-muted mb-0">{{ $user->email }}</p>
                    <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                        <x-badge :status="$user->role"/>
                        @if($user->is_active)
                            <x-badge status="active" label="Active"/>
                        @else
                            <x-badge status="closed" label="Inactive"/>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Joined</span>
                        <span class="fw-medium text-dark">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Email Verified</span>
                        <span class="fw-medium text-dark">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y') : 'Not verified' }}
                        </span>
                    </div>
                    @if($user->deleted_at)
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Deleted</span>
                        <span class="fw-medium text-danger">{{ $user->deleted_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-4 d-flex flex-column gap-2">
                    <form method="POST" action="{{ route('users.toggle-active', $user) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="btn btn-sm w-100 {{ $user->is_active ? 'btn-warning' : 'btn-success' }}">
                            {{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}
                        </button>
                    </form>

                    @if(auth()->id() !== $user->id && !$user->trashed())
                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                          data-bc-confirm="Delete this user account? This cannot be undone.">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            Delete Account
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column gap-4">
                <!-- Linked Resident -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="small fw-semibold text-secondary mb-3">Linked Resident Profile</h3>
                    @if($user->resident)
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="fw-medium text-dark mb-0">{{ $user->resident->full_name }}</p>
                            <p class="small text-muted mb-0">Zone {{ $user->resident->zone }} &middot; {{ $user->resident->address }}</p>
                        </div>
                        <a href="{{ route('residents.show', $user->resident) }}"
                           class="small text-primary text-decoration-none">View Profile</a>
                    </div>
                    @else
                    <p class="small text-muted mb-0">No resident profile linked to this account.</p>
                    @endif
                </div>

                <!-- Recent Activity Logs -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="small fw-semibold text-secondary mb-0">Recent Activity</h3>
                        <a href="{{ route('activity-logs.index') }}?user_id={{ $user->id }}"
                           class="small text-primary text-decoration-none">View all</a>
                    </div>
                    @forelse($user->activityLogs->take(10) as $log)
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom border-secondary border-opacity-10">
                        <span class="badge bc-badge bg-secondary bg-opacity-25 text-secondary flex-shrink-0 mt-1">
                            {{ strtoupper($log->action) }}
                        </span>
                        <div class="min-w-0">
                            <p class="small text-secondary mb-0">{{ $log->description }}</p>
                            <p class="small text-muted mt-1 mb-0">{{ $log->created_at->format('M d, Y h:i A') }} &middot; {{ $log->ip_address }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="small text-muted mb-0">No activity recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
