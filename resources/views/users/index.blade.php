@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 mb-0 bc-page-title">Users</h2>
            <p class="small mb-0 bc-page-subtitle">Manage system accounts and roles.</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="btn btn-bc-primary btn-sm rounded-pill d-inline-flex align-items-center gap-2">
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add User
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="bc-filter-bar mb-4" role="search" aria-label="Filter users">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-search bc-filter-field-search--wide">
                <label for="usr-search" class="visually-hidden">Search users</label>
                <input type="search" id="usr-search" name="search" value="{{ request('search') }}"
                       placeholder="Name or email…"
                       class="form-control form-control-sm" autocomplete="off">
            </div>
            <div class="bc-filter-field-select">
                <label for="usr-role" class="visually-hidden">Role</label>
                <select id="usr-role" name="role" class="form-select form-select-sm" title="Role">
                    <option value="">All roles</option>
                    @foreach(\App\Models\User::ROLES as $role)
                        <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="bc-filter-actions">
                <button type="submit" class="btn btn-bc-primary btn-sm">Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
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
                        <th class="text-uppercase small fw-semibold text-muted">Name</th>
                        <th class="text-uppercase small fw-semibold text-muted">Email</th>
                        <th class="text-uppercase small fw-semibold text-muted">Role</th>
                        <th class="text-uppercase small fw-semibold text-muted">Status</th>
                        <th class="text-uppercase small fw-semibold text-muted">Created</th>
                        <th class="text-uppercase small fw-semibold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="{{ $user->trashed() ? 'opacity-50' : '' }}">
                        <td class="text-muted">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 2rem; height: 2rem;">
                                    <span class="text-primary small fw-bold">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </span>
                                </div>
                                <span class="fw-medium text-dark">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $user->email }}</td>
                        <td>
                            <x-badge :status="$user->role"/>
                        </td>
                        <td>
                            @if($user->trashed())
                                <x-badge status="rejected" label="Deleted"/>
                            @elseif($user->is_active)
                                <x-badge status="active" label="Active"/>
                            @else
                                <x-badge status="closed" label="Inactive"/>
                            @endif
                        </td>
                        <td class="text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @if(!$user->trashed())
                                <a href="{{ route('users.edit', $user) }}"
                                   class="text-primary text-decoration-none small fw-medium">Edit</a>

                                <form method="POST" action="{{ route('users.toggle-active', $user) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-link btn-sm p-0 text-decoration-none small fw-medium {{ $user->is_active ? 'text-warning' : 'text-success' }}">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                @if(auth()->id() !== $user->id)
                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline"
                                      data-bc-confirm="Delete this user account? This cannot be undone.">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger text-decoration-none small fw-medium">
                                        Delete
                                    </button>
                                </form>
                                @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5 small">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top">
            <x-pagination :paginator="$users"/>
        </div>
    </div>

</div>

@endsection
