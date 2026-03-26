@extends('layouts.app')

@section('title', 'Blotter Report')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center gap-3 min-w-0">
            <a href="{{ route('reports.index') }}"
               class="bc-back-link text-decoration-none d-inline-flex align-items-center flex-shrink-0">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="min-w-0">
                <h2 class="h5 mb-0 bc-page-title">Blotter Report</h2>
                <p class="small mb-0 bc-page-subtitle">Blotter and dispute analytics for {{ $year }}.</p>
            </div>
        </div>

        <form method="GET" class="bc-filter-bar bc-filter-bar--embed" aria-label="Select report year">
            <div class="bc-filter-bar-inner">
                <div class="bc-filter-field-select">
                    <label for="rep-blot-year" class="visually-hidden">Year</label>
                    <select id="rep-blot-year" name="year" class="form-select form-select-sm" title="Year">
                        @foreach(range(now()->year, now()->year - 4) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bc-filter-actions">
                    <button type="submit" class="btn btn-bc-primary btn-sm">Apply</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="card bc-card p-3 p-md-4 bc-form-panel text-center">
                <p class="display-6 fw-bold text-dark mb-0">{{ number_format($data['total']) }}</p>
                <p class="small text-muted mt-1 mb-0">Total Cases</p>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card bc-card p-3 p-md-4 bc-form-panel text-center">
                <p class="display-6 fw-bold text-success mb-0">{{ number_format($data['resolved']) }}</p>
                <p class="small text-muted mt-1 mb-0">Resolved</p>
            </div>
        </div>
        <div class="col-12 col-sm-4">
            <div class="card bc-card p-3 p-md-4 bc-form-panel text-center">
                <p class="display-6 fw-bold text-danger mb-0">{{ number_format($data['open']) }}</p>
                <p class="small text-muted mt-1 mb-0">Open Cases</p>
            </div>
        </div>
    </div>

    <!-- Resolution Rate -->
    @if($data['total'] > 0)
    <div class="card bc-card p-3 p-md-4 bc-form-panel mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h3 class="small fw-semibold text-secondary mb-0">Resolution Rate</h3>
            <span class="small fw-bold text-success">
                {{ round(($data['resolved'] / $data['total']) * 100) }}%
            </span>
        </div>
        <div class="w-100 bg-secondary bg-opacity-25 rounded-pill" style="height: 0.75rem;">
            <div class="bg-success rounded-pill"
                 style="height: 0.75rem; width: {{ round(($data['resolved'] / $data['total']) * 100) }}%;">
            </div>
        </div>
        <div class="d-flex justify-content-between small text-muted mt-1">
            <span>{{ $data['resolved'] }} resolved</span>
            <span>{{ $data['open'] }} open</span>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- By Incident Type -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Cases by Incident Type</h3>
                @php
                    $incidentTypes = \App\Models\BlotterRecord::INCIDENT_TYPES;
                    $maxType = count($data['by_type']) > 0 ? max($data['by_type']) : 1;
                    $typeColors = [
                        'bg-danger', 'bg-warning', 'bg-warning', 'bg-primary', 'bg-info',
                        'bg-primary', 'bg-danger', 'bg-success', 'bg-secondary',
                    ];
                @endphp
                @forelse($data['by_type'] as $type => $count)
                @php $idx = array_search($type, array_keys($data['by_type'])); @endphp
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="small text-secondary flex-grow-1 text-truncate">{{ $incidentTypes[$type] ?? $type }}</span>
                    <div class="bg-secondary bg-opacity-25 rounded-pill flex-shrink-0" style="width: 8rem; height: 0.4rem;">
                        <div class="{{ $typeColors[$idx % count($typeColors)] }} rounded-pill"
                             style="height: 0.4rem; width: {{ round(($count / $maxType) * 100) }}%;"></div>
                    </div>
                    <span class="small fw-bold text-dark flex-shrink-0" style="width: 2rem; text-align: right;">{{ $count }}</span>
                </div>
                @empty
                <p class="small text-muted mb-0">No data for {{ $year }}.</p>
                @endforelse
            </div>
        </div>

        <!-- By Status -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Cases by Status</h3>
                @php
                    $statusColors = [
                        'open'      => 'bg-warning',
                        'ongoing'   => 'bg-warning',
                        'resolved'  => 'bg-success',
                        'escalated' => 'bg-danger',
                        'closed'    => 'bg-secondary',
                    ];
                    $totalStatus = array_sum($data['by_status']);
                @endphp
                @forelse($data['by_status'] as $status => $count)
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle flex-shrink-0 {{ $statusColors[$status] ?? 'bg-secondary' }}"
                         style="width: 0.75rem; height: 0.75rem;"></div>
                    <span class="small text-secondary text-capitalize flex-grow-1">{{ $status }}</span>
                    <div class="bg-secondary bg-opacity-25 rounded-pill flex-shrink-0" style="width: 8rem; height: 0.4rem;">
                        <div class="{{ $statusColors[$status] ?? 'bg-secondary' }} rounded-pill"
                             style="height: 0.4rem; width: {{ $totalStatus > 0 ? round(($count / $totalStatus) * 100) : 0 }}%;">
                        </div>
                    </div>
                    <span class="small fw-bold text-dark flex-shrink-0" style="width: 2rem; text-align: right;">{{ $count }}</span>
                    <span class="small text-muted flex-shrink-0" style="width: 2.5rem; text-align: right;">
                        {{ $totalStatus > 0 ? round(($count / $totalStatus) * 100) : 0 }}%
                    </span>
                </div>
                @empty
                <p class="small text-muted mb-0">No data for {{ $year }}.</p>
                @endforelse
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="col-12">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Monthly Blotter Cases ({{ $year }})</h3>
                @php
                    $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    $maxMonth = count($data['by_month']) > 0 ? max($data['by_month']) : 1;
                @endphp
                <div class="d-flex align-items-end gap-2" style="height: 8rem;">
                    @foreach($months as $i => $month)
                    @php
                        $val = $data['by_month'][$i + 1] ?? 0;
                        $pct = $maxMonth > 0 ? round(($val / $maxMonth) * 100) : 0;
                        $isCurrentMonth = (now()->month === $i + 1 && now()->year == $year);
                    @endphp
                    <div class="flex-grow-1 d-flex flex-column align-items-center gap-1">
                        <span class="small text-muted fw-medium">{{ $val > 0 ? $val : '' }}</span>
                        <div class="w-100 rounded-top {{ $isCurrentMonth ? 'bg-danger' : 'bg-danger bg-opacity-25' }}"
                             style="height: {{ max($pct, 4) }}px; max-height: 6rem;">
                        </div>
                        <span class="small text-muted">{{ $month }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Monthly Table -->
                <div class="mt-4 overflow-auto">
                    <table class="table table-sm small mb-0">
                        <thead>
                            <tr class="text-muted">
                                @foreach($months as $month)
                                <th class="text-center pb-1 fw-medium">{{ $month }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($months as $i => $month)
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $data['by_month'][$i + 1] ?? 0 }}
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
