@extends('layouts.app')

@section('title', 'My Requests')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 mb-0 bc-page-title">My Certificate Requests</h2>
            <p class="small text-muted mb-0">Track the status of your certificate requests.</p>
        </div>
        <a href="{{ route('my.requests.create') }}"
           class="btn btn-bc-primary btn-sm rounded-pill d-inline-flex align-items-center gap-2">
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Request
        </a>
    </div>

    <!-- Filter -->
    <form method="GET" class="bc-filter-bar mb-4" aria-label="Filter my requests">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-select bc-filter-field-select--wide">
                <label for="my-cr-status" class="visually-hidden">Status</label>
                <select id="my-cr-status" name="status" class="form-select form-select-sm" title="Status">
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
                <a href="{{ route('my.requests') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </div>
    </form>

    <!-- Status Guide -->
    <div class="card bc-card p-3 mb-4">
        <p class="small fw-semibold text-muted mb-2">Status Guide</p>
        <div class="d-flex flex-wrap gap-3 small text-secondary">
            <div class="d-flex align-items-center gap-2">
                <x-badge status="pending"/>
                <span>Waiting for review</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <x-badge status="approved"/>
                <span>Ready for release</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <x-badge status="released"/>
                <span>Certificate released</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <x-badge status="rejected"/>
                <span>Request rejected</span>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    @forelse($requests as $req)
    <div class="card bc-card p-3 p-md-4 bc-form-panel mb-3">
        <div class="d-flex align-items-start justify-content-between gap-4">
            <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <p class="fw-semibold text-dark mb-0">{{ $req->type_name }}</p>
                    <x-badge :status="$req->status"/>
                </div>
                <p class="small text-muted mb-2">{{ $req->purpose }}</p>
                <div class="d-flex flex-wrap gap-4 small text-muted">
                    <span>Requested: {{ $req->created_at->format('M d, Y') }}</span>
                    @if($req->approved_at)
                        <span class="text-success">Approved: {{ $req->approved_at->format('M d, Y') }}</span>
                    @endif
                    @if($req->rejected_at)
                        <span class="text-danger">Rejected: {{ $req->rejected_at->format('M d, Y') }}</span>
                    @endif
                    @if($req->released_at)
                        <span class="text-primary">Released: {{ $req->released_at->format('M d, Y') }}</span>
                    @endif
                    @if($req->or_number)
                        <span>OR#: {{ $req->or_number }}</span>
                    @endif
                </div>
                @if($req->remarks && $req->status === 'rejected')
                <div class="mt-2 alert alert-danger py-2 px-3">
                    <p class="small mb-0">
                        <span class="fw-semibold">Reason:</span> {{ $req->remarks }}
                    </p>
                </div>
                @endif
                @if($req->remarks && $req->status === 'approved')
                <div class="mt-2 alert alert-success py-2 px-3">
                    <p class="small mb-0">
                        <span class="fw-semibold">Note:</span> {{ $req->remarks }}
                    </p>
                </div>
                @endif
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('my.requests.show', $req) }}"
                   class="small text-primary text-decoration-none fw-medium">
                    View
                </a>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-4">
            @php
                $steps = ['pending' => 1, 'approved' => 2, 'released' => 3, 'rejected' => 0];
                $current = $steps[$req->status] ?? 0;
            @endphp
            @if($req->status !== 'rejected')
            <div class="d-flex align-items-center gap-2">
                @foreach(['Submitted', 'Approved', 'Released'] as $i => $step)
                <div class="d-flex align-items-center {{ $i < 2 ? 'flex-grow-1' : '' }}">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 small fw-bold
                                {{ $current > $i ? 'bg-primary text-white' : ($current === $i + 1 ? 'bg-primary bg-opacity-25 text-primary' : 'bg-light text-muted') }}"
                         style="width: 1.5rem; height: 1.5rem;">
                        {{ $i + 1 }}
                    </div>
                    <p class="small mb-0 ms-1 {{ $current > $i ? 'text-primary fw-medium' : 'text-muted' }}">
                        {{ $step }}
                    </p>
                    @if($i < 2)
                    <div class="flex-grow-1 mx-2" style="height: 2px; background: {{ $current > $i + 1 ? 'var(--bs-primary)' : '#dee2e6' }};"></div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="d-flex align-items-center gap-2 small text-danger">
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Request was rejected.
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="card bc-card p-5 text-center">
        <svg class="text-muted mb-3" style="width: 3rem; height: 3rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-muted small fw-medium mb-0">No requests yet.</p>
        <p class="text-muted small mb-0">Use <strong>New Request</strong> at the top of the page to submit your first certificate request.</p>
    </div>
    @endforelse

    <div class="mt-2">
        <x-pagination :paginator="$requests"/>
    </div>

</div>

@endsection
