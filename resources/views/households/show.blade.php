@extends('layouts.app')

@section('title', 'Household Details')

@section('content')
<div class="py-4" style="max-width: 64rem;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('households.index') }}" class="bc-back-link text-decoration-none">&larr;</a>
            <div>
                <h2 class="h5 mb-0 bc-page-title">Household {{ $household->household_number }}</h2>
                <p class="small mb-0 bc-page-subtitle">{{ $household->zone }} &middot; {{ $household->address }}</p>
            </div>
        </div>
        <a href="{{ route('households.edit', $household) }}" class="btn btn-outline-primary btn-sm rounded-pill">Edit</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="bc-form-section-title">Household Summary</h3>
                <div class="small d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between"><span class="text-muted">Head</span><span>{{ $household->head?->full_name ?? 'Not assigned' }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Zone</span><span>{{ $household->zone }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Members</span><span>{{ $household->members->count() }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Created</span><span>{{ $household->created_at->format('M d, Y') }}</span></div>
                </div>
                @if($household->remarks)
                    <hr>
                    <p class="small text-muted mb-1">Remarks</p>
                    <p class="small mb-0">{{ $household->remarks }}</p>
                @endif
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="card bc-card p-3 p-md-4 bc-form-panel">
                <h3 class="bc-form-section-title">Household Members</h3>
                <div class="table-responsive">
                    <table class="table bc-table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Contact</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($household->members as $member)
                                <tr>
                                    <td>{{ $member->full_name }}</td>
                                    <td class="text-capitalize">{{ $member->gender }}</td>
                                    <td>{{ $member->age }}</td>
                                    <td>{{ $member->contact_number ?: '—' }}</td>
                                    <td><a class="small text-primary text-decoration-none" href="{{ route('residents.show', $member) }}">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No members linked to this household yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
