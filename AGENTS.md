# AGENTS.md

## Project Overview

Laravel 12 event management & ticketing platform for Universitas Amikom Yogyakarta. PHP 8.2+, MySQL, Vite + Tailwind CSS 4. Deployed to Vercel via `api/index.php` (vercel-php runtime).

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
npm install
npm run build
```

Dev server (runs PHP server, queue, logs, Vite concurrently):
```bash
composer dev
```

## Commands

| Task | Command |
|---|---|
| Run all tests | `composer test` |
| Run single test class | `php artisan test --filter=TestClassName` |
| Run single test method | `php artisan test --filter=TestClassName::testMethodName` |
| Clear config & test | `composer test` (clears config cache first) |
| Build assets | `npm run build` |
| Dev assets | `npm run dev` |

No linting, formatting, or typecheck tools are configured.

## Database

- Local dev: MySQL on XAMPP (`eventamikom_3373` database)
- Tests: SQLite in-memory (`phpunit.xml` sets `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`)
- Seeders: `DatabaseSeeder` creates one admin user + sample categories + events
- 22 migration files in `database/migrations/` — run `php artisan migrate` after pulling

## Auth & Roles

Three user roles controlled by `role` column on `users` table:

- **admin** — guarded by `admin` middleware alias. Access `/admin/*` routes.
- **organizer** — guarded by `organizer` middleware alias. Requires approved `Organizer` record. Access `/organizer/*` routes.
- **user** — default role. Google OAuth login via `laravel/socialite`.

Middleware aliases registered in `bootstrap/app.php:14-17`:
```php
$middleware->alias([
    'admin' => AdminMiddleware::class,
    'organizer' => OrganizerMiddleware::class,
]);
```

Admin login is NOT Laravel Breeze/Fortify — it's a custom `Admin\AuthController` at `/admin/login`.

## Route Structure

All routes are in `routes/web.php` (no `api.php`). Key groups:

- `/` — public home
- `/events/{event}` — event detail
- `/checkout/{event}`, `/payment/{order_id}`, `/success/{order_id}` — checkout flow
- `/midtrans/callback` — payment webhook (CSRF exempt)
- `/admin/*` — super admin (requires `auth` + `admin` middleware)
- `/organizer/*` — organizer panel (requires `auth` + `organizer` middleware)
- `/checkin`, `/api/checkin` — QR check-in scanner
- `/auth/google`, `/auth/google/callback` — Google OAuth

## Architecture Notes

- **Event ownership**: Events have `owner_type` (admin vs organizer). Admin creates admin-owned events via `Admin\EventController`, organizers create via `Organizer\EventController`.
- **Pricing tiers**: Events support early_bird, presale, and regular pricing with time-based activation (`Event::getCurrentPriceAttribute()`).
- **Payment**: Midtrans Snap integration. Webhook at `/midtrans/callback` processes status. CSRF is exempted for this route in `bootstrap/app.php`.
- **Poster uploads**: Cloudinary via custom `CloudinaryService` (not the Cloudinary Laravel package despite it being in composer.json). Posters can be Cloudinary URLs, local storage paths, or external HTTP URLs (`Event::getPosterUrlAttribute()`).
- **Tickets**: Sent via email (`TicketMail`) + WhatsApp (`WhatsAppService`) after payment. `ticket_sent` flag prevents duplicate sends.
- **Certificates**: Generated as PDF via `barryvdh/laravel-dompdf`. `GenerateAndSendCertificate` job handles async generation.
- **Check-in**: QR token-based. `qr_token` on transactions, scanned via `/api/checkin`.
- **PWA**: Service worker registration in `layouts/app.blade.php`. Manifest at `/manifest.json`.

## Key Models

| Model | Notable |
|---|---|
| `User` | `role` field (admin/organizer/user), `organizer` hasOne relation |
| `Event` | `owner_type`, pricing tiers, `poster_path` (Cloudinary/local/external) |
| `Transaction` | `order_id`, `snap_token`, `qr_token`, `ticket_sent` flag, stock release logic |
| `Organizer` | `status` (approved/pending), linked to User |
| `Coupon` | Discount system for checkout |
| `Certificate` | PDF certificates for event attendees |

## External Services

All configured via `.env`:

- **Midtrans**: `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION`
- **Cloudinary**: `CLOUDINARY_URL` (parsed manually in `CloudinaryService`, not via package)
- **Google OAuth**: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
- **WhatsApp**: custom service in `app/Services/WhatsAppService.php`

## Frontend

- Blade templates with Tailwind CSS 4 (via `@tailwindcss/vite` plugin)
- `layouts/app.blade.php` — public layout (PWA-aware, glass morphism nav)
- `layouts/admin.blade.php` — admin panel layout
- Assets entry points: `resources/css/app.css`, `resources/js/app.js`
- Vite ignores `storage/framework/views/**` during watch

## Testing

- PHPUnit 11 with `RefreshDatabase` trait
- Tests use SQLite in-memory DB (configured in `phpunit.xml`)
- Feature tests in `tests/Feature/` — admin dashboard, transactions, certificates
- No unit tests currently

## Deployment

- Vercel deployment via `vercel.json` + `api/index.php`
- All routes funnel through `api/index.php` (vercel-php@0.7.4)
- Static assets served from `public/build/`, `public/css/`, `public/images/`, `public/js/`
- Production env vars set in `vercel.json` env block (caches in `/tmp`)
