<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\SupabaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if ($supabase->enabled()) {
            $tokens = $supabase->signInWithPassword($credentials['email'], $credentials['password']);
            $sid    = $tokens['user']['id'] ?? null;

            $user = User::query()
                ->where('email', $credentials['email'])
                ->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => 'No application account exists for this email. Contact the barangay office.',
                ]);
            }

            if ($sid) {
                if ($user->supabase_id === null || $user->supabase_id !== $sid) {
                    $user->forceFill(['supabase_id' => $sid])->save();
                }
            }

            if (! $user->is_active) {
                throw ValidationException::withMessages([
                    'email' => 'This account is inactive. Please contact the barangay office.',
                ]);
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            $request->session()->put('supabase_access_token', $tokens['access_token']);
            $request->session()->put('supabase_refresh_token', $tokens['refresh_token']);
        } else {
            if (! Auth::attempt($credentials, $request->boolean('remember'))) {
                throw ValidationException::withMessages([
                    'email' => 'The email or password you entered is incorrect. Please try again.',
                ]);
            }

            $request->session()->regenerate();
        }

        ActivityLogService::logAuth(
            'login',
            Auth::id(),
            'User logged in: '.Auth::user()->email
        );

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request, SupabaseAuthService $supabase)
    {
        $token = $request->session()->get('supabase_access_token');

        ActivityLogService::logAuth(
            'logout',
            Auth::id(),
            'User logged out: '.Auth::user()->email
        );

        Auth::logout();

        if ($supabase->enabled()) {
            $supabase->signOut(is_string($token) ? $token : null);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
