# Barangay Management System (BMS)

Laravel application for barangay operations: residents, certificates, blotter, legislation, events, and messaging.

## Local development

**Requirements:** PHP 8.2+, Composer, Node 20+, SQLite (default) or MySQL/Postgres.

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
npm ci
npm run dev
```

In another terminal (or use `composer run dev` if configured): `php artisan serve` — or visit the URL Vite prints.

## Environment

Copy **`.env.example`** to **`.env`** and fill in values. Never commit **`.env`**.

- **Supabase (auth + optional DB + storage):** see comments in `.env.example`.
- **Production / Vercel:** see **[DEPLOYMENT.md](DEPLOYMENT.md)** for required variables, migrations, and troubleshooting (HTTP 500, routing, Composer on Vercel).

## Tests & build

```bash
php artisan test
npm run build
```

## Deploy

| Target | Notes |
|--------|--------|
| **Vercel** | `vercel.json` + `api/index.php` — full checklist in [DEPLOYMENT.md](DEPLOYMENT.md) |
| **Docker / VPS / PaaS** | See [DEPLOYMENT.md](DEPLOYMENT.md) and `Dockerfile` |

## License

MIT (Laravel components carry their respective licenses.)
