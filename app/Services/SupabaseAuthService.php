<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SupabaseAuthService
{
    public function enabled(): bool
    {
        return (bool) config('supabase.enabled')
            && config('supabase.url') !== ''
            && config('supabase.anon_key') !== '';
    }

    public function adminEnabled(): bool
    {
        return $this->enabled() && config('supabase.service_role_key') !== '';
    }

    /**
     * @return array{access_token: string, refresh_token: string, user: array}
     */
    public function signInWithPassword(string $email, string $password): array
    {
        $json = $this->postAuth('/auth/v1/token?grant_type=password', [
            'email'    => $email,
            'password' => $password,
        ]);

        return $this->normalizeTokenResponse($json);
    }

    /**
     * @return array{access_token: string, refresh_token: string, user: array}
     */
    public function signUp(string $email, string $password, array $userMetadata = []): array
    {
        $payload = [
            'email'    => $email,
            'password' => $password,
        ];
        if ($userMetadata !== []) {
            $payload['data'] = $userMetadata;
        }

        $json = $this->postAuth('/auth/v1/signup', $payload);

        return $this->normalizeTokenResponse($json);
    }

    /**
     * Create a user in Supabase Auth (admin). Requires service role key.
     *
     * @return array{id: string, email: string}
     */
    public function adminCreateUser(string $email, string $password, array $userMetadata = []): array
    {
        if (! $this->adminEnabled()) {
            throw new \RuntimeException('Supabase service role key is not configured.');
        }

        $payload = [
            'email'          => $email,
            'password'       => $password,
            'email_confirm'  => true,
        ];
        if ($userMetadata !== []) {
            $payload['user_metadata'] = $userMetadata;
        }

        $response = Http::withHeaders($this->adminHeaders())
            ->acceptJson()
            ->post($this->baseUrl().'/auth/v1/admin/users', $payload);

        if (! $response->successful()) {
            $this->throwValidationFromResponse($response, 'Could not create account in Supabase.');
        }

        $user = $response->json();

        return [
            'id'    => $user['id'] ?? '',
            'email' => $user['email'] ?? $email,
        ];
    }

    public function adminUpdateUserPassword(string $supabaseId, string $password): void
    {
        if (! $this->adminEnabled()) {
            throw new \RuntimeException('Supabase service role key is not configured.');
        }

        $response = Http::withHeaders($this->adminHeaders())
            ->acceptJson()
            ->put($this->baseUrl().'/auth/v1/admin/users/'.$supabaseId, [
                'password' => $password,
            ]);

        if (! $response->successful()) {
            $this->throwValidationFromResponse($response, 'Could not update password in Supabase.');
        }
    }

    public function signOut(?string $accessToken): void
    {
        if ($accessToken === null || $accessToken === '') {
            return;
        }

        try {
            Http::withHeaders(array_merge(
                $this->anonHeaders(),
                ['Authorization' => 'Bearer '.$accessToken]
            ))->post($this->baseUrl().'/auth/v1/logout');
        } catch (\Throwable) {
            // Best-effort remote sign-out
        }
    }

    private function baseUrl(): string
    {
        return config('supabase.url');
    }

    private function anonHeaders(): array
    {
        return [
            'apikey'        => config('supabase.anon_key'),
            'Content-Type'  => 'application/json',
        ];
    }

    private function adminHeaders(): array
    {
        $key = config('supabase.service_role_key');

        return [
            'apikey'        => $key,
            'Authorization' => 'Bearer '.$key,
            'Content-Type'  => 'application/json',
        ];
    }

    private function postAuth(string $path, array $body): array
    {
        $response = Http::withHeaders($this->anonHeaders())
            ->acceptJson()
            ->post($this->baseUrl().$path, $body);

        if (! $response->successful()) {
            $this->throwValidationFromResponse($response);
        }

        return $response->json();
    }

    /**
     * @param  array<string, mixed>  $json
     * @return array{access_token: string, refresh_token: string, user: array}
     */
    private function normalizeTokenResponse(array $json): array
    {
        $user = $json['user'] ?? [];
        if (! is_array($user)) {
            $user = [];
        }

        return [
            'access_token'  => (string) ($json['access_token'] ?? ''),
            'refresh_token' => (string) ($json['refresh_token'] ?? ''),
            'user'          => $user,
        ];
    }

    private function throwValidationFromResponse(\Illuminate\Http\Client\Response $response, ?string $fallback = null): void
    {
        $msg    = $fallback ?? 'Authentication failed.';
        $body   = $response->json();
        $detail = is_array($body) ? ($body['error_description'] ?? $body['msg'] ?? $body['message'] ?? null) : null;
        if (is_string($detail) && $detail !== '') {
            $msg = $detail;
        } elseif (is_string($body['error'] ?? null)) {
            $msg = (string) $body['error'];
        }

        throw ValidationException::withMessages([
            'email' => $msg,
        ]);
    }
}
