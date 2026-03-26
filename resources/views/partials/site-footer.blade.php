<footer class="bc-landing-footer">
    <div class="bc-landing-footer-inner px-3">
        <div class="text-center text-md-start mb-3 mb-md-0">
            <p class="bc-landing-footer-brand mb-1">Barangay Cantupa</p>
            <p class="bc-landing-footer-meta mb-2">La Libertad, Negros Oriental · Philippines</p>
            <nav class="bc-landing-footer-links d-flex flex-wrap justify-content-center justify-content-md-start gap-2 gap-md-3" aria-label="Footer">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('home') }}#services">Services</a>
                <a href="{{ route('home') }}#about">About</a>
                <a href="{{ route('events.index') }}">Announcements</a>
                <a href="{{ route('home') }}#contact">Contact</a>
                @guest
                    <a href="{{ route('login') }}">Sign in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @else
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @endguest
            </nav>
        </div>
        <p class="bc-landing-footer-meta mb-0 text-center text-md-end">
            &copy; {{ now()->year }} Barangay Cantupa. Civic digital services.
        </p>
    </div>
</footer>
