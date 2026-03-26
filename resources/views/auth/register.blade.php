@extends('layouts.guest')

@section('title', 'Create account')

@section('content')

    <h2 class="h5 fw-bold mb-1" style="color: var(--bc-text);">Create an account</h2>
    <p class="small text-muted mb-4">Register as a resident to request certificates and message the barangay office.</p>

    <form method="POST" action="{{ route('register.store') }}" class="bc-auth-form">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label small fw-medium">Full name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="form-control form-control-lg {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   required autofocus autocomplete="name" placeholder="Juan Dela Cruz">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label small fw-medium">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   required autocomplete="email" placeholder="you@example.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3" x-data="{ show: false }">
            <label for="password" class="form-label small fw-medium">Password</label>
            <div class="input-group input-group-lg">
                <input :type="show ? 'text' : 'password'" id="password" name="password"
                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       required autocomplete="new-password">
                <button type="button" class="btn btn-outline-secondary" @click="show = !show" title="Toggle visibility">
                    <span x-show="!show"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></span>
                    <span x-show="show" x-cloak><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg></span>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4" x-data="{ show2: false }">
            <label for="password_confirmation" class="form-label small fw-medium">Confirm password</label>
            <div class="input-group input-group-lg">
                <input :type="show2 ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                       class="form-control"
                       required autocomplete="new-password">
                <button type="button" class="btn btn-outline-secondary" @click="show2 = !show2" title="Toggle visibility">
                    <span x-show="!show2"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></span>
                    <span x-show="show2" x-cloak><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg></span>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-bc-primary btn-lg w-100 py-3 fw-semibold rounded-pill">
            Create account
        </button>
    </form>

    <p class="text-center small text-muted mt-4 mb-0">
        Already have an account? <a href="{{ route('login') }}" class="bc-link">Sign in</a>
    </p>

@endsection
