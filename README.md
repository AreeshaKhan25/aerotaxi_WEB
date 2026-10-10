<h1 align="center">AeroTAXI Web Platform</h1>

<p align="center">
  Airport transfer booking for twelve UK airports: quote, book, pay and manage,
  with a full admin back office.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3+">
  <img src="https://img.shields.io/badge/Tailwind-4-38B2AC?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4">
  <img src="https://img.shields.io/badge/Stripe-test_mode-008CDD?style=flat-square&logo=stripe&logoColor=white" alt="Stripe">
  <img src="https://img.shields.io/badge/Turso-libSQL-4FF8D2?style=flat-square" alt="Turso">
</p>

---

## Overview

AeroTAXI is a complete airport transfer booking platform. A customer picks up and drop
off points, sees live per-vehicle pricing, adds extras, pays through Stripe and receives
a confirmation email with a reference they can look up later. Staff get a separate admin
area covering bookings, fleet, coverage zones, promotions, subscribers and contact
messages.

This repository is the **Laravel edition**. The same product also exists as a
[Flutter mobile app](https://github.com/AreeshaKhan25/AeroTAXI) and as a
[dependency-free classical PHP build](https://github.com/AreeshaKhan25/aerotaxi-php).

---

## Architecture

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13 (PHP 8.3+) |
| Frontend | Blade templates + Tailwind CSS 4, bundled with Vite |
| Local database | SQLite (`database/database.sqlite`) |
| Cloud database | Turso (libSQL) |
| Payments | Stripe PHP SDK v20, test mode |
| Transactional email | Resend |
| Flight lookup | AviationStack, with a regex fallback parser |

### Dual-database strategy

The app reads and writes local SQLite during development, and syncs with Turso on demand:

```bash
php artisan turso:sync   # push local SQLite up to Turso
php artisan turso:pull   # pull Turso down into local SQLite
```

This keeps local development fast and offline-capable while still having a single shared
cloud copy for deployed environments.

---

## Features

### Customer booking flow

- **Quote**: pickup/dropoff with airport autocomplete across twelve UK airports
  (LHR, LGW, STN, LTN, LCY, MAN, BHX, EDI, BRS, NCL, BFS, SEN)
- **Vehicle selection**: saloon, estate, executive, people carrier, minibus and 16-seater,
  each priced independently
- **Flight validation**: flight number checked against AviationStack, falling back to a
  regex parse of the airline code and number when no API key is configured
- **Payment**: Stripe PaymentIntent created server-side, confirmed client-side
- **Confirmation**: booking reference issued, emails sent to both customer and admin
- **Lookup**: customers retrieve an existing booking by reference without an account

### Admin back office

| Screen | Purpose |
|--------|---------|
| Dashboard | Booking volume and revenue at a glance |
| Bookings | List, filter and open individual booking details |
| Fleet | Manage vehicle types and per-mile pricing |
| Zones map | Coverage areas |
| Promotions | Compose and send promotional email to subscribers |
| Subscribers | Newsletter list |
| Contact messages | Enquiry inbox with read/unread state |
| Stats | Reporting views |

Admin routes are protected by the `AdminAuth` middleware; notifications are persisted in
`admin_notifications` so nothing is missed between sessions.

---

## Getting started

### Prerequisites

| Tool | Minimum |
|------|---------|
| PHP | 8.3 |
| Composer | 2.x |
| Node.js | 18+ |

### Install

```bash
git clone https://github.com/AreeshaKhan25/aerotaxi_WEB.git
cd aerotaxi_WEB

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed
```

### Run

```bash
npm run dev          # Vite dev server
php artisan serve    # http://127.0.0.1:8000
```

### Configure

Set these in `.env` before exercising payment or email:

```ini
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
RESEND_API_KEY=...
AVIATIONSTACK_KEY=...      # optional; falls back to regex parsing
TURSO_DATABASE_URL=...     # optional; only needed for cloud sync
TURSO_AUTH_TOKEN=...
```

Stripe runs in **test mode**, so use Stripe's published test card numbers. No real charge
is ever made.

A full walkthrough, including PHP extension requirements and troubleshooting, is in
[`SETUP.md`](SETUP.md).

---

## Deployment

Two paths are configured:

- **Docker**: `Dockerfile` and `docker-start.sh` build a self-contained image
- **Vercel**: `vercel.json` and `api/index.php` run Laravel on Vercel's PHP runtime

---

## Project structure

```
app/
  Http/Controllers/        Home, Booking, Contact, Coverage, Help, Legal
  Http/Controllers/Admin/  Auth, Dashboard
  Http/Middleware/         AdminAuth
  Models/                  Booking, Airport, Vehicle, Faq, ContactMessage,
                           Subscriber, Admin, AdminNotification
  Mail/                    BookingConfirmation, AdminBookingNotification
  Console/Commands/        TursoSync, TursoPull
database/migrations/       8 migrations
resources/views/           Blade templates: public, booking, admin, legal, emails
routes/web.php             All public and admin routes
```

---

## Testing

```bash
php artisan test
```

---

## License

Released under the [MIT License](LICENSE).
