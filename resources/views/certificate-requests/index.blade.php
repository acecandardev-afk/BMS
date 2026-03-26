@extends('layouts.app')

@section('title', 'Certificate Requests')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 mb-0 bc-page-title">Certificate Requests</h2>
            <p class="small mb-0 bc-page-subtitle">Manage and process certificate requests.</p>
        </div>
    </div>

    <!-- Filters — compact toolbar -->
    <form method="GET" class="bc-filter-bar mb-4" role="search" aria-label="Filter certificate requests">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-search">
                <label for="cr-filter-search" class="visually-hidden">Search by resident name</label>
                <input type="search" id="cr-filter-search" name="search" value="{{ request('search') }}"
                       placeholder="Search resident…"
                       class="form-control form-control-sm" autocomplete="off">
            </div>
            <div class="bc-filter-field-select">
                <label for="cr-filter-type" class="visually-hidden">Certificate type</label>
                <select id="cr-filter-type" name="type" class="form-select form-select-sm" title="Type">
                    <option value="">All types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bc-filter-field-select">
                <label for="cr-filter-status" class="visually-hidden">Status</label>
                <select id="cr-filter-status" name="status" class="form-select form-select-sm" title="Status">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="bc-filter-actions">
                <button type="submit" class="btn btn-bc-primary btn-sm">Filter</button>
                <a href="{{ route('certificate-requests.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card bc-card overflow-hidden">
        <div class="table-responsive">
            <table class="table bc-table table-hover table-striped mb-0 small">
                <thead>
                    <tr>
                        <th class="text-uppercase small fw-semibold text-muted">#</th>
                        <th class="text-uppercase small fw-semibold text-muted">Resident</th>
                        <th class="text-uppercase small fw-semibold text-muted">Type</th>
                        <th class="text-uppercase small fw-semibold text-muted">Purpose</th>
                        <th class="text-uppercase small fw-semibold text-muted">Status</th>
                        <th class="text-uppercase small fw-semibold text-muted">Date</th>
                        <th class="text-uppercase small fw-semibold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr>
                        <td class="text-muted">{{ $requests->firstItem() + $loop->index }}</td>
                        <td>
                            <p class="fw-medium text-dark mb-0">{{ $req->resident?->full_name ?? '—' }}</p>
                            <p class="small text-muted mb-0">{{ $req->resident?->zone }}</p>
                        </td>
                        <td class="text-secondary">{{ $req->type_name }}</td>
                        <td class="text-secondary text-truncate" style="max-width: 12rem;">{{ $req->purpose }}</td>
                        <td>
                            <x-badge :status="$req->status"/>
                        </td>
                        <td class="text-muted">{{ $req->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <a href="{{ route('certificate-requests.show', $req) }}"
                                   class="text-primary text-decoration-none small fw-medium">View</a>

                                @can('approve', $req)
                                @if($req->status === 'pending')
                                <form method="POST" action="{{ route('certificate-requests.approve', $req) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-success text-decoration-none small fw-medium">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('certificate-requests.reject', $req) }}" class="d-inline"
                                      data-bc-confirm="Reject this certificate request?">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger text-decoration-none small fw-medium">
                                        Reject
                                    </button>
                                </form>
                                @endif
                                @endcan

                                @can('release', $req)
                                @if($req->status === 'approved')
                                <form method="POST" action="{{ route('certificate-requests.release', $req) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-primary text-decoration-none small fw-medium">
                                        Release
                                    </button>
                                </form>
                                @endif
                                @endcan

                                @can('print', $req)
                                @if(in_array($req->status, ['approved', 'released']))
                                <a href="{{ route('certificate-requests.print', $req) }}"
                                   target="_blank"
                                   class="text-secondary text-decoration-none small fw-medium">
                                    Print
                                </a>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5 small">
                            No certificate requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top">
            <x-pagination :paginator="$requests"/>
        </div>
    </div>

</div>

@endsection
