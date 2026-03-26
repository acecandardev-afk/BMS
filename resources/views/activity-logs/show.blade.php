@extends('layouts.app')

@section('title', 'Audit Log Detail')

@section('content')

<div class="py-4" style="max-width: 48rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('activity-logs.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">Audit Log Detail</h2>
            <p class="small text-muted mb-0">#{{ $activityLog->id }}</p>
        </div>
    </div>

    <div class="d-flex flex-column gap-4">
        <!-- Main Info -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Log Information
            </h3>
            <div class="row g-3 small">
                <div class="col-12 col-sm-6">
                    <p class="text-muted small mb-0">Action</p>
                    @php
                    $actionColors = [
                        'create'  => 'bg-success bg-opacity-25 text-success',
                        'update'  => 'bg-primary bg-opacity-25 text-primary',
                        'delete'  => 'bg-danger bg-opacity-25 text-danger',
                        'approve' => 'bg-success bg-opacity-25 text-success',
                        'reject'  => 'bg-danger bg-opacity-25 text-danger',
                        'release' => 'bg-primary bg-opacity-25 text-primary',
                        'print'   => 'bg-secondary bg-opacity-25 text-secondary',
                        'login'   => 'bg-warning bg-opacity-25 text-dark',
                        'logout'  => 'bg-secondary bg-opacity-25 text-secondary',
                        'restore' => 'bg-info bg-opacity-25 text-info',
                    ];
                    $colorClass = $actionColors[$activityLog->action] ?? 'bg-secondary bg-opacity-25 text-secondary';
                    @endphp
                    <span class="badge bc-badge {{ $colorClass }}">
                        {{ strtoupper($activityLog->action) }}
                    </span>
                </div>
                <div class="col-12 col-sm-6">
                    <p class="text-muted small mb-0">Timestamp</p>
                    <p class="fw-medium text-dark mb-0">{{ $activityLog->created_at->format('F d, Y h:i:s A') }}</p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-0">Description</p>
                    <p class="fw-medium text-dark mb-0">{{ $activityLog->description }}</p>
                </div>
                <div class="col-12 col-sm-6">
                    <p class="text-muted small mb-0">Subject Type</p>
                    <p class="fw-medium text-dark mb-0">
                        {{ $activityLog->subject_type ? class_basename($activityLog->subject_type) : '—' }}
                    </p>
                </div>
                <div class="col-12 col-sm-6">
                    <p class="text-muted small mb-0">Subject ID</p>
                    <p class="fw-medium text-dark mb-0">{{ $activityLog->subject_id ?? '—' }}</p>
                </div>
            </div>
        </div>

        <!-- Actor Info -->
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Performed By
            </h3>
            <div class="row g-3 small">
                <div class="col-12 col-sm-6">
                    <p class="text-muted small mb-0">User</p>
                    <p class="fw-medium text-dark mb-0">{{ $activityLog->user?->name ?? 'System' }}</p>
                </div>
                <div class="col-12 col-sm-6">
                    <p class="text-muted small mb-0">Role</p>
                    <p class="fw-medium text-dark mb-0 text-capitalize">{{ $activityLog->user?->role ?? '—' }}</p>
                </div>
                <div class="col-12 col-sm-6">
                    <p class="text-muted small mb-0">IP Address</p>
                    <p class="fw-medium text-dark mb-0">{{ $activityLog->ip_address ?? '—' }}</p>
                </div>
                <div class="col-12 col-sm-6">
                    <p class="text-muted small mb-0">User Agent</p>
                    <p class="fw-medium text-dark mb-0 small text-truncate d-block" style="max-width: 100%;">{{ $activityLog->user_agent ?? '—' }}</p>
                </div>
            </div>
        </div>

        <!-- Old Values -->
        @if($activityLog->old_values)
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                Previous Values
            </h3>
            <div class="bg-danger bg-opacity-10 rounded p-3 overflow-auto">
                <table class="table table-sm small mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted fw-semibold pb-2" style="width: 33%;">Field</th>
                            <th class="text-muted fw-semibold pb-2">Value</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-danger border-opacity-25">
                        @foreach($activityLog->old_values as $field => $value)
                        <tr>
                            <td class="text-secondary fw-medium py-1">{{ str_replace('_', ' ', ucfirst($field)) }}</td>
                            <td class="text-danger py-1">
                                @if(is_array($value))
                                    {{ json_encode($value) }}
                                @else
                                    {{ $value ?? 'null' }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- New Values -->
        @if($activityLog->new_values)
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <h3 class="bc-form-section-title">
                New Values
            </h3>
            <div class="bg-success bg-opacity-10 rounded p-3 overflow-auto">
                <table class="table table-sm small mb-0">
                    <thead>
                        <tr>
                            <th class="text-muted fw-semibold pb-2" style="width: 33%;">Field</th>
                            <th class="text-muted fw-semibold pb-2">Value</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-success border-opacity-25">
                        @foreach($activityLog->new_values as $field => $value)
                        <tr>
                            <td class="text-secondary fw-medium py-1">{{ str_replace('_', ' ', ucfirst($field)) }}</td>
                            <td class="text-success py-1">
                                @if(is_array($value))
                                    {{ json_encode($value) }}
                                @else
                                    {{ $value ?? 'null' }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

</div>

@endsection
