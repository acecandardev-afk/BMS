# Deployment (production)

This app is a **Laravel** PHP application. **Vercel** is optimized for serverless frontends and Node; running a full Laravel stack on Vercel is possible only via community PHP runtimes and is **not** the path most teams use.

## Recommended hosting

Deploy the Laravel app to a **container** or **PHP-friendly** platform, then point your domain there:

- **Fly.io**, **Railway**, **Render**, **DigitalOcean App Platform**, **Laravel Cloud**, or any **VPS** with Docker.
- Use the included `Dockerfile` as a starting point (adjust PHP extensions and web server as needed).

Keep **GitHub** as the source of truth; connect the host’s “deploy from repo” to your branch.

## Environment variables (production)

| Variable | Purpose |
|----------|---------|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `php artisan key:generate --show` (set once, keep secret) |
| `APP_URL` | Public HTTPS URL of the app |
| `DB_*` or `DATABASE_URL` | Prefer **Supabase Postgres** in production (`pgsql` / connection string from Supabase) |
| `SESSION_DRIVER` | Use `database` or `redis` (avoid `file` on multi-instance) |
| `CACHE_STORE` | `database` or `redis` |
| `QUEUE_CONNECTION` | `database` or `redis` if you process queues |
| `SUPABASE_ENABLED` | `true` when using Supabase Auth |
| `SUPABASE_URL`, `SUPABASE_ANON_KEY` | Project **API** settings |
| `SUPABASE_SERVICE_ROLE_KEY` | **Server only** — for admin user create / password sync |
| `FILESYSTEM_UPLOAD_DISK` | `supabase` when using Supabase Storage |
| `SUPABASE_STORAGE_*` | S3 endpoint, keys, bucket, and **public URL** base for the bucket |

Never commit `.env` or service role keys. Use the host’s secret store.

## Security & GitHub

- **`.env`** is gitignored. Copy **`.env.example`** → **`.env`** on each machine and server; never push `.env`.
- **`.env.example`** must contain **only placeholders** (no real URLs, keys, or secrets). Real keys belong only in `.env` or your host’s secret manager.
- **Rotate credentials** in Supabase (Dashboard → API / JWT settings) if keys were ever committed or shared.
- **Production:** `APP_DEBUG=false`, `APP_ENV=production`, HTTPS URL in `APP_URL`. Consider `SESSION_ENCRYPT=true` when using HTTPS.
- **No separate REST API** is exposed: routes are web + `health` at `/up`. There is no `routes/api.php` unless you add one.
- **Service role key** must only exist on the server; never expose it to the browser or frontend bundles.

## Supabase Auth

1. Enable `SUPABASE_ENABLED=true` and set URL + anon key.
2. Set `SUPABASE_SERVICE_ROLE_KEY` on the server so staff-created accounts can be provisioned in Supabase Auth.
3. Run migrations so `users.supabase_id` exists and backfill existing users if you migrate from password-only auth.

If **email confirmation** is enabled in Supabase, self-registration may return without a session until the user confirms email; adjust Supabase Auth settings if you want immediate login after signup.

## Supabase Storage

1. Create a bucket (e.g. `uploads`).
2. In **Storage → S3**, create access keys and set `SUPABASE_STORAGE_*` env vars.
3. Set `SUPABASE_STORAGE_PUBLIC_URL` to the public object base for that bucket, for example:  
   `https://<project-ref>.supabase.co/storage/v1/object/public/<bucket>`  
   so generated file URLs match how Supabase serves public objects.
4. Set `FILESYSTEM_UPLOAD_DISK=supabase`.

## Build & migrate on the server

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
npm ci && npm run build
```

## Vercel (optional / advanced)

This repo includes **`vercel.json`**, **`api/index.php`** (forwards to `public/index.php`), and **`.vercelignore`** so Vercel does not upload `vendor` / `node_modules` (they are installed on the build machine).

**In the Vercel dashboard**, set **Output Directory** to `public` (or rely on `vercel.json` — it should match). Clear any old setting that expected **`dist`** (that was causing “No Output Directory named dist”).

**Why `npm run build` only (no `composer` in `buildCommand`):** Vercel’s **Node** install/build steps do **not** have `composer` or `php` on `PATH`, so any shell command that calls them returns **exit 127**. The **[vercel-php](https://github.com/vercel-community/php)** runtime runs **`composer install`** itself when it bundles your **`api/**`** PHP functions (see `runComposerInstall` in that repo). So **`installCommand`** = `npm ci`, **`buildCommand`** = `npm run build` only; **`vendor/`** is produced during the PHP lambda build, not in the Node build step.

**Serverless constraints**

- Use a **hosted database** (e.g. Supabase Postgres). **SQLite on the serverless filesystem is not suitable** for production traffic.
- Prefer **`SESSION_DRIVER=cookie`** (or a remote DB session store). **`CACHE_STORE=array`** avoids writing cache files to a read-only disk.
- Set **writable paths** via environment variables (Vercel project → Settings → Environment Variables), for example:

  `VIEW_COMPILED_PATH=/tmp/views`  
  `APP_CONFIG_CACHE=/tmp/config.php`  
  `APP_ROUTES_CACHE=/tmp/routes-v7.php`  
  `APP_SERVICES_CACHE=/tmp/services.php`  
  `APP_PACKAGES_CACHE=/tmp/packages.php`  
  `APP_EVENTS_CACHE=/tmp/events.php`  

- Set **`APP_KEY`**, **`APP_URL`** (your `https://…vercel.app` or custom domain), **`APP_ENV=production`**, **`APP_DEBUG=false`**, and all Supabase/DB secrets in the dashboard — **never** commit them.

PHP runtime: **[vercel-community/php](https://github.com/vercel-community/php)** (`vercel-php@0.7.4` in `vercel.json`). If a deploy fails routing, check Vercel’s build logs; older projects sometimes need the **routes** block adjusted.

If you prefer fewer constraints, host the full app on **Fly.io / Railway / Render** with Docker (above) instead.
