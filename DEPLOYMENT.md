# Deployment (production)

## Quick checklist (Vercel)

1. **GitHub** connected; latest **`main`** deployed.
2. **Vercel → Settings → Environment Variables (Production):** `APP_KEY`, `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`, **`DATABASE_URL`** + `DB_CONNECTION=pgsql` (Supabase), Supabase keys if you use Supabase Auth.
3. **Run migrations once** against that database: `php artisan migrate --force` (from your PC with the same `DATABASE_URL` in `.env`, or any safe method).
4. **Redeploy** after changing env vars.
5. Open **`/up`** — expect **200**. If **500**, see **HTTP 500 on Vercel** below and **Function logs**.

---

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

**Important:** Vercel’s dashboard environment variables are what the PHP runtime sees reliably. Root **`env` in `vercel.json`** is not always applied the same way for community PHP runtimes. The app detects **`VERCEL`** (set automatically by the platform) and uses **stderr** logging, **array** cache, **sync** queue, and **`/tmp/views`** for compiled Blade unless you override via env — so you do not have to duplicate those in the dashboard if you only set **`APP_*`**, **`DATABASE_URL`**, and **`DB_CONNECTION`**.

**In the Vercel dashboard**, set **Output Directory** to `public` (or rely on `vercel.json` — it should match). Clear any old setting that expected **`dist`** (that was causing “No Output Directory named dist”).

**“Authenticated” then the browser downloads a file:** Vercel **Deployment Protection** shows the login screen first; that is normal if protection is enabled. If the **next** page **downloads** instead of showing HTML, the usual cause was **`routes` with `{ "handle": "filesystem" }`**: Vercel then serves `public/index.php` as a **static file**, so PHP is not executed. This repo uses **explicit** routes for `/build/*` and static files, then sends everything else to **`/api/index.php`** so Laravel runs. To remove the extra “Authenticated” step for public visitors, open **Project → Settings → Deployment Protection** and adjust (e.g. disable for Production or only protect Preview).

**Why `npm run build` only (no `composer` in `buildCommand`):** Vercel’s **Node** install/build steps do **not** have `composer` or `php` on `PATH`, so any shell command that calls them returns **exit 127**. The **[vercel-php](https://github.com/vercel-community/php)** runtime runs **`composer install`** itself when it bundles your **`api/**`** PHP functions (see `runComposerInstall` in that repo). So **`installCommand`** = `npm ci`, **`buildCommand`** = `npm run build` only; **`vendor/`** is produced during the PHP lambda build, not in the Node build step.

**Serverless constraints**

- Use a **hosted database** (e.g. Supabase Postgres). **SQLite on the serverless filesystem is not suitable** for production traffic.
- **`SESSION_DRIVER=cookie`** (default on Vercel via `config/session.php` + `vercel.json`) avoids writing sessions to Postgres on every request (common source of **500** on `/register` when the pooler or `sessions` table misbehaves). **Do not store Supabase JWTs in the Laravel session** — they are too large for the encrypted cookie. Remote Supabase logout is best-effort without a stored access token. You can set **`SESSION_DRIVER=database`** in the dashboard if you prefer DB sessions and have a reliable **`sessions`** table.
- **`CACHE_STORE=array`** avoids writing cache files to a read-only disk.
- Set **writable paths** via environment variables (Vercel project → Settings → Environment Variables), for example:

  `VIEW_COMPILED_PATH=/tmp/views`  
  `APP_CONFIG_CACHE=/tmp/config.php`  
  `APP_ROUTES_CACHE=/tmp/routes-v7.php`  
  `APP_SERVICES_CACHE=/tmp/services.php`  
  `APP_PACKAGES_CACHE=/tmp/packages.php`  
  `APP_EVENTS_CACHE=/tmp/events.php`  

- Set **`APP_KEY`**, **`APP_URL`** (your `https://…vercel.app` or custom domain), **`APP_ENV=production`**, **`APP_DEBUG=false`**, and all Supabase/DB secrets in the dashboard — **never** commit them.

PHP runtime: **[vercel-community/php](https://github.com/vercel-community/php)** (`vercel-php@0.7.4` in `vercel.json`). If a deploy fails routing, check Vercel’s build logs; older projects sometimes need the **routes** block adjusted.

**HTTP 500 after the site loads (no Deployment Protection):** The app needs a **working Postgres** connection for **data** (users, residents, etc.). **`vercel.json`** sets **`SESSION_DRIVER=cookie`**, **`CACHE_STORE=array`**, **`QUEUE_CONNECTION=sync`**, **`LOG_CHANNEL=stderr`**, and **`/tmp`** paths for compiled views/config caches — but you **must** still add secrets in **Vercel → Settings → Environment Variables**. **Do not** set `SESSION_DRIVER=database` unless you have run migrations and a stable connection to the **`sessions`** table. If you use Supabase’s **transaction pooler** (port **6543**), keep **`DB_EMULATE_PREPARES`** at the default (**true** in `config/database.php`) or you may see prepared-statement errors.

| Required | Example |
|----------|---------|
| `APP_KEY` | Output of `php artisan key:generate --show` (32-byte base64) |
| `APP_URL` | `https://bms-vert.vercel.app` (your real hostname) |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` (set `true` briefly only to read an error, then turn off) |
| Database | `DB_CONNECTION=pgsql` and `DATABASE_URL=postgresql://...` (Supabase **Connection string**, **Session** or **Transaction** pooler), **or** individual `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` |

Run **`php artisan migrate --force`** against that database once (from your machine with `DATABASE_URL`, or via a one-off script). Then open **`/up`** — if it returns **200**, Laravel boots; if **500**, check **Vercel → Deployment → Logs** (Function / Runtime) for the stack trace.

**Function memory:** `vercel.json` sets **`memory`: 3008** MB for the PHP lambda (allowed on **Pro**). On **Hobby**, the maximum is **1024** MB — if deployment fails validation, set **`memory`** to **1024** in `vercel.json`. **`api/php.ini`** sets a higher PHP **`memory_limit`** for the runtime.

If you prefer fewer constraints, host the full app on **Fly.io / Railway / Render** with Docker (above) instead.
