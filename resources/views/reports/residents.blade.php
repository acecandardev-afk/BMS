@extends('layouts.app')

@section('title', 'Residents Report')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('reports.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">Residents Report</h2>
            <p class="small mb-0 bc-page-subtitle">Demographic breakdown of registered residents.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        @php
        $summaries = [
            ['label' => 'Voters',      'value' => $data['voters'],      'color' => 'green'],
            ['label' => 'PWD',         'value' => $data['pwd'],         'color' => 'blue'],
            ['label' => 'Indigenous',  'value' => $data['indigenous'],  'color' => 'yellow'],
            ['label' => 'Solo Parent', 'value' => $data['solo_parent'], 'color' => 'red'],
            ['label' => '4Ps',         'value' => $data['fourps'],      'color' => 'gray'],
        ];
        @endphp
        @foreach($summaries as $s)
        <div class="col-6 col-sm-4 col-lg-2">
            <div class="card bc-card p-3 text-center">
                <p class="h4 fw-bold text-dark mb-0">{{ number_format($s['value']) }}</p>
                <p class="small text-muted mb-0 mt-1">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <!-- By Zone -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Residents by Zone</h3>
                @php $maxZone = count($data['by_zone']) > 0 ? max($data['by_zone']) : 1; @endphp
                @forelse($data['by_zone'] as $zone => $count)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="small text-secondary flex-shrink-0" style="width: 5rem;">{{ $zone }}</span>
                    <div class="flex-grow-1 bg-secondary bg-opacity-25 rounded-pill" style="height: 0.4rem;">
                        <div class="bg-primary rounded-pill"
                             style="height: 0.4rem; width: {{ round(($count / $maxZone) * 100) }}%;"></div>
                    </div>
                    <span class="small fw-bold text-dark flex-shrink-0" style="width: 2rem; text-align: right;">{{ $count }}</span>
                </div>
                @empty
                <p class="small text-muted mb-0">No data.</p>
                @endforelse
            </div>
        </div>

        <!-- By Gender -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">Residents by Gender</h3>
                @php $total = array_sum($data['by_gender']); @endphp
                @forelse($data['by_gender'] as $gender => $count)
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
                                {{ $gender === 'male' ? 'bg-primary bg-opacity-25 text-primary' : 'bg-danger bg-opacity-25 text-danger' }}"
                         style="width: 2.5rem; height: 2.5rem;">
                        <span class="small fw-bold">{{ strtoupper(substr($gender, 0, 1)) }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-capitalize text-secondary">{{ $gender }}</span>
                            <span class="fw-semibold text-dark">
                                {{ $count }} ({{ $total > 0 ? round(($count / $total) * 100) : 0 }}%)
                            </span>
                        </div>
                        <div class="bg-secondary bg-opacity-25 rounded-pill" style="height: 0.5rem;">
                            <div class="{{ $gender === 'male' ? 'bg-primary' : 'bg-danger' }} rounded-pill"
                                 style="height: 0.5rem; width: {{ $total > 0 ? round(($count / $total) * 100) : 0 }}%;"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="small text-muted mb-0">No data.</p>
                @endforelse
            </div>
        </div>

        <!-- By Civil Status -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">By Civil Status</h3>
                @php
                    $totalCivil = array_sum($data['by_civil_status']);
                    $civilColors = ['single' => 'bg-primary', 'married' => 'bg-success', 'widowed' => 'bg-secondary', 'separated' => 'bg-warning', 'annulled' => 'bg-danger'];
                @endphp
                <div class="d-flex flex-column gap-2">
                    @forelse($data['by_civil_status'] as $status => $count)
                    <div class="d-flex align-items-center gap-3">
                        <span class="small text-secondary flex-shrink-0 text-capitalize" style="width: 6rem;">{{ $status }}</span>
                        <div class="flex-grow-1 bg-secondary bg-opacity-25 rounded-pill" style="height: 0.5rem;">
                            <div class="{{ $civilColors[$status] ?? 'bg-secondary' }} rounded-pill"
                                 style="height: 0.5rem; width: {{ $totalCivil > 0 ? round(($count / $totalCivil) * 100) : 0 }}%;"></div>
                        </div>
                        <span class="small fw-semibold text-dark flex-shrink-0" style="width: 2rem; text-align: right;">{{ $count }}</span>
                    </div>
                    @empty
                    <p class="small text-muted mb-0">No data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- By Age Group -->
        <div class="col-12 col-lg-6">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="small fw-semibold text-secondary mb-3">By Age Group</h3>
                @php
                    $totalAge = array_sum($data['by_age_group']);
                    $ageColors = ['Minor (0-17)' => 'bg-warning', 'Young Adult (18-35)' => 'bg-primary', 'Adult (36-59)' => 'bg-success', 'Senior (60+)' => 'bg-secondary'];
                @endphp
                <div class="d-flex flex-column gap-2">
                    @forelse($data['by_age_group'] as $group => $count)
                    <div class="d-flex align-items-center gap-3">
                        <span class="small text-secondary flex-shrink-0" style="width: 9rem;">{{ $group }}</span>
                        <div class="flex-grow-1 bg-secondary bg-opacity-25 rounded-pill" style="height: 0.5rem;">
                            <div class="{{ $ageColors[$group] ?? 'bg-primary' }} rounded-pill"
                                 style="height: 0.5rem; width: {{ $totalAge > 0 ? round(($count / $totalAge) * 100) : 0 }}%;"></div>
                        </div>
                        <span class="small fw-semibold text-dark flex-shrink-0" style="width: 2rem; text-align: right;">{{ $count }}</span>
                    </div>
                    @empty
                    <p class="small text-muted mb-0">No data.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
