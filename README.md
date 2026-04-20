# CA Project

A Laravel 12 application for managing listings, contracts, orders, payments, and messaging between users and farmers.

## Features

- Listing management
- Contract creation, negotiation, signing, cancellation, and delivery tracking
- Order and invoice viewing
- Payment processing and farmer payment confirmation
- In-app contract messaging with attachment download support
- Farmer dashboard routes for contract requests, orders, payments, and messages

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- A database supported by Laravel (SQLite, MySQL, PostgreSQL, etc.)

## Setup

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Install JavaScript dependencies:

   ```bash
   npm install
   ```

3. Copy environment file and generate an application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure database connection values in `.env`.

5. Run database migrations:

   ```bash
   php artisan migrate
   ```

## Development

Start the development environment:

```bash
npm run dev
php artisan serve
```

If you want to run the combined Laravel/Vite workflow, use:

```bash
composer run dev
```

## Testing

Run PHPUnit tests:

```bash
php artisan test
```

## Useful Composer Scripts

- `composer setup` – install dependencies, create `.env`, generate app key, migrate, install npm packages, and build assets.
- `composer dev` – starts the Laravel server, queue listener, log watcher, and Vite dev server.
- `composer test` – clears config cache and runs tests.

## Project Structure

- `app/Http/Controllers` – application controllers
- `app/Models` – Eloquent models
- `resources/views` – Blade templates
- `routes/web.php` – web routes
- `database/migrations` – database schema definitions
- `tests` – automated tests

## Notes

This project uses Tailwind CSS, Vite, Alpine.js, and Laravel Breeze for frontend scaffolding and asset bundling.
