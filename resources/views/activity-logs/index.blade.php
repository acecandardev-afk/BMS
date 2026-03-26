@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="h5 mb-0 bc-page-title">Audit Logs</h2>
        <p class="small mb-0 bc-page-subtitle">Track all critical system actions and changes.</p>
    </div>

    <!-- Filters -->
    <form method="GET" class="bc-filter-bar mb-4" aria-label="Filter audit logs">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-search">
                <label for="log-search" class="visually-hidden">Search logs</label>
                <input type="search" id="log-search" name="search" value="{{ request('search') }}"
                       placeholder="Description, IP…"
                       class="form-control form-control-sm" autocomplete="off">
            </div>
            <div class="bc-filter-field-select bc-filter-field-select--user">
                <label for="log-user" class="visually-hidden">User</label>
                <select id="log-user" name="user_id" class="form-select form-select-sm" title="User">
                    <option value="">All users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="bc-filter-field-select">
                <label for="log-action" class="visually-hidden">Action</label>
                <select id="log-action" name="action" class="form-select form-select-sm" title="Action">
                    <option value="">All actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="bc-filter-field-date">
                <label for="log-from" class="visually-hidden">From date</label>
                <input type="date" id="log-from" name="date_from" value="{{ request('date_from') }}"
                       class="form-control form-control-sm" title="From">
            </div>
            <div class="bc-filter-field-date">
                <label for="log-to" class="visually-hidden">To date</label>
                <input type="date" id="log-to" name="date_to" value="{{ request('date_to') }}"
                       class="form-control form-control-sm" title="To">
            </div>
            <div class="bc-filter-actions">
                <button type="submit" class="btn btn-bc-primary btn-sm">Filter</button>
                <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card bc-card overflow-hidden">
        <div class="table-responsive">
            <table class="table bc-table table-hover table-striped mb-0 small">
                <thead>
                    <tr>
                        <th class="text-uppercase small fw-semibold text-muted">Time</th>
                        <th class="text-uppercase small fw-semibold text-muted">User</th>
                        <th class="text-uppercase small fw-semibold text-muted">Action</th>
                        <th class="text-uppercase small fw-semibold text-muted">Description</th>
                        <th class="text-uppercase small fw-semibold text-muted">Subject</th>
                        <th class="text-uppercase small fw-semibold text-muted">IP</th>
                        <th class="text-uppercase small fw-semibold text-muted"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="text-muted text-nowrap">
                            <p class="small mb-0">{{ $log->created_at->format('M d, Y') }}</p>
                            <p class="small text-muted mb-0">{{ $log->created_at->format('h:i A') }}</p>
                        </td>
                        <td>
                            <p class="fw-medium text-dark small mb-0">{{ $log->user?->name ?? 'System' }}</p>
                            <p class="small text-muted mb-0 text-capitalize">{{ $log->user?->role }}</p>
                        </td>
                        <td>
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
                            $colorClass = $actionColors[$log->action] ?? 'bg-secondary bg-opacity-25 text-secondary';
                            @endphp
                            <span class="badge bc-badge {{ $colorClass }}">
                                {{ strtoupper($log->action) }}
                            </span>
                        </td>
                        <td class="text-secondary" style="max-width: 12rem;">
                            <p class="small text-truncate mb-0">{{ $log->description }}</p>
                        </td>
                        <td class="text-muted">
                            @if($log->subject_type)
                            <p class="small mb-0">{{ class_basename($log->subject_type) }}</p>
                            <p class="small text-muted mb-0">#{{ $log->subject_id }}</p>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $log->ip_address }}</td>
                        <td>
                            <a href="{{ route('activity-logs.show', $log) }}"
                               class="small text-primary text-decoration-none">Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5 small">
                            No activity logs found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top">
            <x-pagination :paginator="$logs"/>
        </div>
    </div>

</div>

@endsection
