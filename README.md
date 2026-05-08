# test-amopoint

Laravel application with a small IQAir measurement backend and a JavaScript demo for dynamic field visibility by selected type.

## Features

- Fetch and store a configured IQAir city measurement.
- Return latest IQAir measurements from a JSON API endpoint.
- Demonstrate standalone JavaScript that hides or shows fields based on `select[name="type_val"]`.

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm
- SQLite by default

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

You can also use the project setup script:

```bash
composer run setup
```

## Development

Run the Laravel server, queue listener, logs, and Vite dev server together:

```bash
composer run dev
```

Run tests:

```bash
php artisan test --compact
```

## IQAir Configuration

Set these values in `.env` before fetching measurements:

```ini
IQAIR_API_KEY=
IQAIR_CITY=
IQAIR_STATE=
IQAIR_COUNTRY=
IQAIR_BASE_URL=https://api.airvisual.com/v2
```

Fetch the configured city measurement:

```bash
php artisan iqair:fetch-city
```

Read latest measurements:

```http
GET /api/iqair-measurements
```

## Type Field Visibility Demo

The demo page is available at:

```text
/type-field-visibility-demo
```

It loads the standalone script from:

```text
/js/type-field-visibility.js
```

## License

MIT
