# ShopX

ShopX is a multi-vendor shop. The storefront, vendor desk, and admin desk are a React app. Laravel exposes the same behavior as a JSON API.

## Stack

- React 19, React Router, and Tailwind, in `frontend/src`
- Laravel 12 and PHP 8.3, with Sanctum bearer tokens
- SQLite for local development

Reusable interface pieces live in `frontend/src/components` (buttons, fields, modals, tables, navigation). Pages live in `frontend/src/pages`. API calls go through `frontend/src/services/api.js` and `frontend/src/hooks/useAuth.jsx`.

## Run it locally

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve --host=127.0.0.1 --port=43124
```

In another terminal:

```bash
cd frontend
npm install
npm run dev
```

Open http://127.0.0.1:43123. The Vite server proxies `/api` to Laravel.

Seeded accounts, password `12345678`:

- Customer: `user@gmail.com`
- Vendor: `vendor@gmail.com` (KYC already approved, store Northwind Goods)
- Admin: `superadmin@gmail.com`

## API

Authenticated requests send `Authorization: Bearer <token>`. Customer and vendor tokens come from `POST /api/auth/login`. Admin tokens come from `POST /api/admin/login`. CORS origins are `CORS_ALLOWED_ORIGINS` in `.env`.

Commerce tables added for the screens that previously had no data: products, product images, addresses, carts, orders, and wishlists.
