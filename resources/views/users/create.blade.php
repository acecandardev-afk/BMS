@extends('layouts.app')

@section('title', 'Add User')

@section('content')

<div class="py-4" style="max-width: 42rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('users.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="h5 mb-0 bc-page-title">Add User</h2>
            <p class="small text-muted mb-0">Create a new system account.</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('users.store') }}"
          class="card bc-card p-3 p-md-4 bc-form-panel d-flex flex-column gap-4">
        @csrf

        <!-- Name -->
        <div>
            <label class="form-label small fw-medium text-secondary">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="form-control form-control-sm {{ $errors->has('name') ? 'is-invalid' : '' }}">
            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <!-- Email -->
        <div>
            <label class="form-label small fw-medium text-secondary">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="form-control form-control-sm {{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <!-- Role -->
        <div>
            <label class="form-label small fw-medium text-secondary">Role</label>
            <select name="role" required
                    class="form-select form-select-sm {{ $errors->has('role') ? 'is-invalid' : '' }}">
                <option value="">Select role...</option>
                @foreach(\App\Models\User::ROLES as $role)
                    <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
            @error('role')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label class="form-label small fw-medium text-secondary">Password</label>
            <div class="position-relative">
                <input :type="show ? 'text' : 'password'" name="password" required
                       class="form-control form-control-sm pe-5 {{ $errors->has('password') ? 'is-invalid' : '' }}">
                <button type="button" @click="show = !show"
                        class="position-absolute top-50 end-0 translate-middle-y pe-3 btn btn-link btn-sm text-muted p-0">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <label class="form-label small fw-medium text-secondary">Confirm Password</label>
            <div class="position-relative">
                <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                       class="form-control form-control-sm pe-5">
                <button type="button" @click="show = !show"
                        class="position-absolute top-50 end-0 translate-middle-y pe-3 btn btn-link btn-sm text-muted p-0">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Is Active -->
        <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', true) ? 'checked' : '' }}
                   class="form-check-input">
            <label for="is_active" class="form-check-label small text-secondary">Account is active</label>
        </div>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-3 pt-2">
            <button type="submit" class="btn btn-bc-primary btn-sm rounded-pill">
                Create User
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection
