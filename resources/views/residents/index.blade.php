@extends('layouts.app')

@section('title', 'Residents')

@section('content')

<div class="py-4">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="h5 mb-1 bc-page-title">Residents Registry</h2>
            <p class="small mb-0 bc-page-subtitle">Manage resident profiles and records.</p>
        </div>
        <a href="{{ route('residents.create') }}" class="btn btn-bc-primary btn-sm rounded-pill">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Resident
        </a>
    </div>

    <form method="GET" class="bc-filter-bar mb-4" role="search" aria-label="Filter residents">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-search">
                <label for="res-search" class="visually-hidden">Search residents</label>
                <input type="search" id="res-search" name="search" value="{{ request('search') }}" placeholder="Search name or contact…"
                       class="form-control form-control-sm" autocomplete="off">
            </div>
            <div class="bc-filter-field-select">
                <label for="res-zone" class="visually-hidden">Zone</label>
                <select id="res-zone" name="zone" class="form-select form-select-sm" title="Zone">
                    <option value="">All zones</option>
                    @foreach(range(1, 10) as $zone)
                        <option value="Zone {{ $zone }}" {{ request('zone') === "Zone $zone" ? 'selected' : '' }}>Zone {{ $zone }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bc-filter-field-select">
                <label for="res-status" class="visually-hidden">Status</label>
                <select id="res-status" name="status" class="form-select form-select-sm" title="Status">
                    <option value="">All status</option>
                    <option value="voter" {{ request('status') === 'voter' ? 'selected' : '' }}>Voters</option>
                    <option value="pwd" {{ request('status') === 'pwd' ? 'selected' : '' }}>PWD</option>
                    <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div class="bc-filter-actions">
                <button type="submit" class="btn btn-bc-primary btn-sm">Filter</button>
                <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </div>
    </form>

    <div class="card bc-card overflow-hidden">
        <div class="table-responsive">
            <table class="table bc-table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Zone</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Contact</th>
                        <th>Tags</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $resident)
                    <tr class="{{ $resident->trashed() ? 'opacity-50' : '' }}">
                        <td class="text-muted">{{ $residents->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($resident->photo)
                                    <img src="{{ $resident->photo_url }}" class="rounded-circle object-fit-cover" style="width: 32px; height: 32px;">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0 small fw-bold" style="width: 32px; height: 32px;">
                                        {{ strtoupper(substr($resident->first_name, 0, 1) . substr($resident->last_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="mb-0 fw-medium">{{ $resident->full_name }}</p>
                                    @if($resident->household)
                                        <p class="mb-0 small text-muted">HH# {{ $resident->household->household_number }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $resident->zone }}</td>
                        <td class="text-capitalize">{{ $resident->gender }}</td>
                        <td>{{ $resident->age ?? '—' }}</td>
                        <td>{{ $resident->contact_number ?? '—' }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @if($resident->voter_status) <x-badge status="approved" label="Voter"/> @endif
                                @if($resident->is_pwd) <x-badge status="ongoing" label="PWD"/> @endif
                                @if($resident->is_4ps) <x-badge status="pending" label="4Ps"/> @endif
                                @if($resident->is_solo_parent) <x-badge status="released" label="Solo Parent"/> @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('residents.show', $resident) }}" class="small text-primary text-decoration-none">View</a>
                                @if(!$resident->trashed())
                                <a href="{{ route('residents.edit', $resident) }}" class="small text-secondary text-decoration-none">Edit</a>
                                <form method="POST" action="{{ route('residents.destroy', $resident) }}" class="d-inline" data-bc-confirm="Delete this resident record?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 small text-danger text-decoration-none border-0">Delete</button>
                                </form>
                                @else
                                <a href="{{ route('residents.restore', $resident->id) }}" class="small text-success text-decoration-none">Restore</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">No residents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            <x-pagination :paginator="$residents"/>
        </div>
    </div>

</div>

@endsection
