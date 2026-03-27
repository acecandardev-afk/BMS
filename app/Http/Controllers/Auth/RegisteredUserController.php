<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\SupabaseAuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request, SupabaseAuthService $supabase)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $result = DB::transaction(function () use ($validated, $supabase) {
            if ($supabase->enabled()) {
                $tokens = $supabase->signUp($validated['email'], $validated['password'], [
                    'name' => $validated['name'],
                ]);
                $sid = $tokens['user']['id'] ?? null;

                $user = User::create([
                    'name'        => $validated['name'],
                    'email'       => $validated['email'],
                    'password'    => Hash::make($validated['password']),
                    'role'        => User::ROLE_RESIDENT,
                    'is_active'   => true,
                    'supabase_id' => $sid,
                ]);

                $user->ensureResidentProfile();

                return ['user' => $user, 'tokens' => $tokens];
            }

            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role'      => User::ROLE_RESIDENT,
                'is_active' => true,
            ]);

            $user->ensureResidentProfile();

            return ['user' => $user, 'tokens' => null];
        });

        $user = $result['user'];

        event(new Registered($user));

        ActivityLogService::logCreate($user, "Self-registered resident: {$user->email}");

        Auth::login($user);

        $request->session()->regenerate();

        // Do not store Supabase JWTs in session: cookie driver is size-limited (~4KB) and breaks requests.

        return redirect()->route('dashboard')
            ->with('success', 'Welcome! Your account has been created.');
    }
}
