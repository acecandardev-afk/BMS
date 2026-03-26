@extends('layouts.app')

@section('title', 'Households')

@section('content')
<div class="py-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h2 class="h5 mb-1 bc-page-title">Household Registry</h2>
            <p class="small mb-0 bc-page-subtitle">Manage household records and members.</p>
        </div>
        <a href="{{ route('households.create') }}" class="btn btn-bc-primary btn-sm rounded-pill">Add Household</a>
    </div>

    <form method="GET" class="bc-filter-bar mb-4" role="search" aria-label="Filter households">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-search bc-filter-field-search--wide">
                <label for="hh-search" class="visually-hidden">Search households</label>
                <input type="search" id="hh-search" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Number or address…" autocomplete="off">
            </div>
            <div class="bc-filter-field-tag">
                <label for="hh-zone" class="visually-hidden">Zone</label>
                <input type="text" id="hh-zone" name="zone" value="{{ request('zone') }}" class="form-control form-control-sm" placeholder="Zone…" autocomplete="off">
            </div>
            <div class="bc-filter-actions">
                <button type="submit" class="btn btn-bc-primary btn-sm">Filter</button>
                <a href="{{ route('households.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </div>
    </form>

    <div class="card bc-card overflow-hidden">
        <div class="table-responsive">
            <table class="table bc-table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Household #</th>
                        <th>Zone</th>
                        <th>Address</th>
                        <th>Head</th>
                        <th>Members</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($households as $household)
                        <tr>
                            <td class="fw-medium">{{ $household->household_number }}</td>
                            <td>{{ $household->zone }}</td>
                            <td>{{ $household->address }}</td>
                            <td>{{ $household->head?->full_name ?? 'Not assigned' }}</td>
                            <td>{{ $household->members_count }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('households.show', $household) }}" class="small text-primary text-decoration-none">View</a>
                                    <a href="{{ route('households.edit', $household) }}" class="small text-secondary text-decoration-none">Edit</a>
                                    <form method="POST" action="{{ route('households.destroy', $household) }}" data-bc-confirm="Delete this household record?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 small text-danger text-decoration-none border-0">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">No households found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            <x-pagination :paginator="$households" />
        </div>
    </div>
</div>
@endsection
