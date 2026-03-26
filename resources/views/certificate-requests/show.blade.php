@extends('layouts.app')

@section('title', 'Certificate Request Detail')

@section('content')

<div class="py-4" style="max-width: 56rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ auth()->user()->isResident() ? route('my.requests') : route('certificate-requests.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-grow-1">
            <h2 class="h5 mb-0 bc-page-title">Certificate Request</h2>
            <p class="small text-muted mb-0">#{{ $certificateRequest->id }} &middot; {{ $certificateRequest->type_name }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <x-badge :status="$certificateRequest->status"/>
            @can('print', $certificateRequest)
            @if(in_array($certificateRequest->status, ['approved', 'released']))
            <a href="{{ route('certificate-requests.print', $certificateRequest) }}"
               target="_blank"
               class="btn btn-secondary btn-sm d-inline-flex align-items-center gap-2">
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Certificate
            </a>
            @endif
            @endcan
        </div>
    </div>

    <div class="row g-4">
        <!-- Request Info -->
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column gap-4">
                <!-- Details Card -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="bc-form-section-title">
                        Request Details
                    </h3>
                    <div class="row g-3 small">
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">Certificate Type</p>
                            <p class="fw-medium text-dark mb-0">{{ $certificateRequest->type_name }}</p>
                        </div>
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">Status</p>
                            <x-badge :status="$certificateRequest->status"/>
                        </div>
                        <div class="col-12">
                            <p class="text-muted mb-0">Purpose</p>
                            <p class="fw-medium text-dark mb-0">{{ $certificateRequest->purpose }}</p>
                        </div>
                        @if($certificateRequest->or_number)
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">OR Number</p>
                            <p class="fw-medium text-dark mb-0">{{ $certificateRequest->or_number }}</p>
                        </div>
                        @endif
                        @if($certificateRequest->fee)
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">Fee</p>
                            <p class="fw-medium text-dark mb-0">₱{{ number_format($certificateRequest->fee, 2) }}</p>
                        </div>
                        @endif
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">Date Requested</p>
                            <p class="fw-medium text-dark mb-0">{{ $certificateRequest->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($certificateRequest->approved_at)
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">Date Approved</p>
                            <p class="fw-medium text-dark mb-0">{{ $certificateRequest->approved_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                        @if($certificateRequest->rejected_at)
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">Date Rejected</p>
                            <p class="fw-medium text-danger mb-0">{{ $certificateRequest->rejected_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                        @if($certificateRequest->released_at)
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">Date Released</p>
                            <p class="fw-medium text-dark mb-0">{{ $certificateRequest->released_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                        @if($certificateRequest->printed_at)
                        <div class="col-12 col-sm-6">
                            <p class="text-muted mb-0">Last Printed</p>
                            <p class="fw-medium text-dark mb-0">{{ $certificateRequest->printed_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @endif
                        @if($certificateRequest->remarks)
                        <div class="col-12">
                            <p class="text-muted mb-0">Remarks</p>
                            <p class="fw-medium text-dark mb-0">{{ $certificateRequest->remarks }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Approve/Reject Form -->
                @can('approve', $certificateRequest)
                @if($certificateRequest->status === 'pending')
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="bc-form-section-title">
                        Review Request
                    </h3>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <form method="POST" action="{{ route('certificate-requests.approve', $certificateRequest) }}">
                                @csrf @method('PATCH')
                                <div class="mb-3">
                                    <label class="form-label small fw-medium text-secondary">Remarks (optional)</label>
                                    <textarea name="remarks" rows="3" placeholder="Add approval remarks..."
                                              class="form-control form-control-sm"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    Approve Request
                                </button>
                            </form>
                        </div>
                        <div class="col-12 col-sm-6">
                            <form method="POST" action="{{ route('certificate-requests.reject', $certificateRequest) }}"
                                  data-bc-confirm="Reject this certificate request?">
                                @csrf @method('PATCH')
                                <div class="mb-3">
                                    <label class="form-label small fw-medium text-secondary">Reason for Rejection <span class="text-danger">*</span></label>
                                    <textarea name="remarks" rows="3" required placeholder="State reason for rejection..."
                                              class="form-control form-control-sm"></textarea>
                                </div>
                                <button type="submit"
                                        class="btn btn-danger btn-sm w-100">
                                    Reject Request
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                @endcan

                <!-- Release -->
                @can('release', $certificateRequest)
                @if($certificateRequest->status === 'approved')
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="bc-form-section-title">
                        Release Certificate
                    </h3>
                    <p class="small text-muted mb-3">
                        Mark this certificate as released once the resident has claimed it.
                    </p>
                    <form method="POST" action="{{ route('certificate-requests.release', $certificateRequest) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-bc-primary btn-sm rounded-pill">
                            Mark as Released
                        </button>
                    </form>
                </div>
                @endif
                @endcan
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-lg-4">
            <div class="d-flex flex-column gap-4">
                <!-- Resident Info -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="small fw-semibold text-secondary mb-3">Resident</h3>
                    @if($certificateRequest->resident)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 2.5rem; height: 2.5rem;">
                            <span class="text-primary small fw-bold">
                                @php
                                    $ia = strtoupper(substr($certificateRequest->resident->first_name ?? '', 0, 1));
                                    $ib = strtoupper(substr($certificateRequest->resident->last_name ?? '', 0, 1));
                                @endphp
                                {{ $ib !== '' ? $ia.$ib : $ia }}
                            </span>
                        </div>
                        <div>
                            <p class="fw-medium text-dark small mb-0">{{ $certificateRequest->resident->full_name }}</p>
                            <p class="small text-muted mb-0">{{ $certificateRequest->resident->zone ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2 small">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Age</span>
                            <span class="fw-medium text-secondary">{{ $certificateRequest->resident->age ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Civil Status</span>
                            <span class="fw-medium text-secondary text-capitalize">{{ $certificateRequest->resident->civil_status ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Address</span>
                            <span class="fw-medium text-secondary text-end" style="max-width: 8.75rem;">{{ $certificateRequest->resident->address ?? '—' }}</span>
                        </div>
                    </div>
                    @can('manage-residents')
                    <a href="{{ route('residents.show', $certificateRequest->resident) }}"
                       class="mt-3 d-block text-center small text-primary text-decoration-none">
                        View Full Profile
                    </a>
                    @endcan
                    @else
                    <p class="small text-muted mb-0">No resident linked.</p>
                    @endif
                </div>

                <!-- Processed By -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="small fw-semibold text-secondary mb-3">Processing Info</h3>
                    <div class="d-flex flex-column gap-2 small">
                        <div>
                            <p class="text-muted small mb-0">Requested By</p>
                            <p class="fw-medium text-secondary mb-0">{{ $certificateRequest->requester?->name ?? '—' }}</p>
                        </div>
                        @if($certificateRequest->signatory)
                        <div>
                            <p class="text-muted small mb-0">Signatory</p>
                            <p class="fw-medium text-secondary mb-0">{{ $certificateRequest->signatory->name }}</p>
                        </div>
                        @endif
                        @if($certificateRequest->processor)
                        <div>
                            <p class="text-muted small mb-0">Released By</p>
                            <p class="fw-medium text-secondary mb-0">{{ $certificateRequest->processor->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
