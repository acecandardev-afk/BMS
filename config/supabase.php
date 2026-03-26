<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supabase (auth + optional Postgres)
    |--------------------------------------------------------------------------
    |
    | Get keys from: Project Settings → API (URL, anon key, service_role).
    | JWT secret: Project Settings → API → JWT Settings.
    |
    */

    'enabled' => env('SUPABASE_ENABLED', false),

    'url' => rtrim(env('SUPABASE_URL', ''), '/'),

    'anon_key' => env('SUPABASE_ANON_KEY', ''),

    'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY', ''),

    'jwt_secret' => env('SUPABASE_JWT_SECRET', ''),

];
