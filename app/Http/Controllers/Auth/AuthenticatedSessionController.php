<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\SupabaseAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request, SupabaseAuthService $supabase)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if ($supabase->enabled()) {
            try {
                $tokens = $supabase->signInWithPassword($credentials['email'], $credentials['password']);
            } catch (ValidationException $e) {
                // Seeded / legacy accounts exist only in Laravel (supabase_id null), not in Supabase Auth.
                if ($user && $user->supabase_id === null && Hash::check($credentials['password'], $user->password)) {
                    return $this->finishWebLogin($request, $user);
                }

                throw $e;
            }

            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => 'No application account exists for this email. Contact the barangay office.',
                ]);
            }

            $sid = $tokens['user']['id'] ?? null;
            if ($sid) {
                if ($user->supabase_id === null || $user->supabase_id !== $sid) {
                    $user->forceFill(['supabase_id' => $sid])->save();
                }
            }

            return $this->finishWebLogin($request, $user);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'The email or password you entered is incorrect. Please try again.',
            ]);
        }

        return $this->finishWebLogin($request, Auth::user());
    }

    /**
     * Complete login after the user is authenticated (Supabase path, Laravel-only fallback, or Auth::attempt).
     */
    private function finishWebLogin(Request $request, User $user): RedirectResponse
    {
        if (! $user->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'This account is inactive. Please contact the barangay office.',
            ]);
        }

        if (! Auth::check()) {
            Auth::login($user, $request->boolean('remember'));
        }

        $request->session()->regenerate();

        ActivityLogService::logAuth(
            'login',
            $user->id,
            'User logged in: '.$user->email
        );

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request, SupabaseAuthService $supabase)
    {
        ActivityLogService::logAuth(
            'logout',
            Auth::id(),
            'User logged out: '.Auth::user()->email
        );

        Auth::logout();

        if ($supabase->enabled()) {
            $supabase->signOut(null);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
