@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="h5 mb-0 bc-page-title">Reports & Analytics</h2>
        <p class="small mb-0 bc-page-subtitle">Summary of barangay data and operations.</p>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <x-stat-card label="Total Residents" :value="$data['total_residents']" color="blue"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-stat-card label="Registered Voters" :value="$data['total_voters']" color="green"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-stat-card label="Total Requests" :value="$data['total_requests']" color="yellow"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-stat-card label="Open Blotter Cases" :value="$data['open_blotter']" color="red"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>'/>
        </div>
    </div>

    <!-- Report Modules -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <a href="{{ route('reports.residents') }}"
               class="card bc-card p-3 p-md-4 bc-form-panel text-decoration-none text-dark">
                <div class="rounded-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mb-3" style="width: 3rem; height: 3rem;">
                    <svg class="text-primary" style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="fw-semibold text-dark mb-1">Residents Report</h3>
                <p class="small text-muted mb-0">Demographics, zone distribution, classifications.</p>
            </a>
        </div>
        <div class="col-12 col-sm-4">
            <a href="{{ route('reports.certificates') }}"
               class="card bc-card p-3 p-md-4 bc-form-panel text-decoration-none text-dark">
                <div class="rounded-3 bg-warning bg-opacity-10 d-flex align-items-center justify-content-center mb-3" style="width: 3rem; height: 3rem;">
                    <svg class="text-warning" style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="fw-semibold text-dark mb-1">Certificates Report</h3>
                <p class="small text-muted mb-0">Request volume, turnaround time, type breakdown.</p>
            </a>
        </div>
        <div class="col-12 col-sm-4">
            <a href="{{ route('reports.blotter') }}"
               class="card bc-card p-3 p-md-4 bc-form-panel text-decoration-none text-dark">
                <div class="rounded-3 bg-danger bg-opacity-10 d-flex align-items-center justify-content-center mb-3" style="width: 3rem; height: 3rem;">
                    <svg class="text-danger" style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <h3 class="fw-semibold text-dark mb-1">Blotter Report</h3>
                <p class="small text-muted mb-0">Incident types, resolution rates, monthly trends.</p>
            </a>
        </div>
    </div>

    <!-- Residents by Zone -->
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Residents by Zone</h3>
                @forelse($data['residents_by_zone'] as $zone => $count)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="small text-secondary flex-shrink-0" style="width: 5rem;">{{ $zone }}</span>
                    <div class="flex-grow-1 bg-secondary bg-opacity-25 rounded-pill" style="height: 0.5rem;">
                        @php
                            $max = max($data['residents_by_zone']);
                            $pct = $max > 0 ? round(($count / $max) * 100) : 0;
                        @endphp
                        <div class="bg-primary rounded-pill" style="height: 0.5rem; width: {{ $pct }}%;"></div>
                    </div>
                    <span class="small fw-semibold text-dark flex-shrink-0" style="width: 2rem; text-align: right;">{{ $count }}</span>
                </div>
                @empty
                <p class="small text-muted mb-0">No data available.</p>
                @endforelse
            </div>
        </div>

        <!-- Requests by Type -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Certificate Requests by Type</h3>
                @php $types = \App\Models\CertificateRequest::TYPES; @endphp
                @forelse($data['requests_by_type'] as $type => $count)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="small text-secondary flex-grow-1 text-truncate">{{ $types[$type] ?? $type }}</span>
                    <div class="bg-secondary bg-opacity-25 rounded-pill flex-shrink-0" style="width: 6rem; height: 0.5rem;">
                        @php
                            $maxReq = max($data['requests_by_type']);
                            $pctReq = $maxReq > 0 ? round(($count / $maxReq) * 100) : 0;
                        @endphp
                        <div class="bg-warning rounded-pill" style="height: 0.5rem; width: {{ $pctReq }}%;"></div>
                    </div>
                    <span class="small fw-semibold text-dark flex-shrink-0" style="width: 2rem; text-align: right;">{{ $count }}</span>
                </div>
                @empty
                <p class="small text-muted mb-0">No data available.</p>
                @endforelse
            </div>
        </div>

        <!-- Requests by Month -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Certificate Requests This Year</h3>
                @php
                    $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    $maxMonth = count($data['requests_by_month']) > 0 ? max($data['requests_by_month']) : 1;
                @endphp
                <div class="d-flex align-items-end gap-1" style="height: 6rem;">
                    @foreach($months as $i => $month)
                    @php
                        $val = $data['requests_by_month'][$i + 1] ?? 0;
                        $height = $maxMonth > 0 ? round(($val / $maxMonth) * 100) : 0;
                    @endphp
                    <div class="flex-grow-1 d-flex flex-column align-items-center gap-1">
                        <span class="small text-muted">{{ $val > 0 ? $val : '' }}</span>
                        <div class="w-100 bg-primary bg-opacity-25 rounded-top"
                             style="height: {{ max($height, 4) }}px; max-height: 4.5rem;">
                        </div>
                        <span class="small text-muted">{{ $month }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Blotter by Type -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Blotter by Incident Type</h3>
                @php $incidentTypes = \App\Models\BlotterRecord::INCIDENT_TYPES; @endphp
                @forelse($data['blotter_by_type'] as $type => $count)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="small text-secondary flex-grow-1 text-truncate">{{ $incidentTypes[$type] ?? $type }}</span>
                    <div class="bg-secondary bg-opacity-25 rounded-pill flex-shrink-0" style="width: 6rem; height: 0.5rem;">
                        @php
                            $maxBlt = max($data['blotter_by_type']);
                            $pctBlt = $maxBlt > 0 ? round(($count / $maxBlt) * 100) : 0;
                        @endphp
                        <div class="bg-danger rounded-pill" style="height: 0.5rem; width: {{ $pctBlt }}%;"></div>
                    </div>
                    <span class="small fw-semibold text-dark flex-shrink-0" style="width: 2rem; text-align: right;">{{ $count }}</span>
                </div>
                @empty
                <p class="small text-muted mb-0">No data available.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection
