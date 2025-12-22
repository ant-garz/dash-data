# dash-data

A web-based application for working with **dash cam video footage** and associated data.

## Features

- Convert or change **video codecs** to suit different playback or archival needs.
- Upload and **stitch together multiple video clips** (e.g., 2-minute dash cam segments into a continuous 1-hour drive).
- Frontend built for responsiveness and usability with modern Svelte UI components.
- RESTful backend for secure, extensible video processing workflows.

---

## Tech Stack

**Frontend:**
- [Svelte](https://svelte.dev/)
- [Bootstrap](https://getbootstrap.com/) via [Sveltestrap](https://github.com/sveltestrap/sveltestrap)
- [Inertia.js](https://inertiajs.com/) (for server-driven single-page apps)

**Backend:**
- [Laravel](https://laravel.com/docs)
- MySQL

**Development Environment Tools:**
- [Docker](https://www.docker.com/)
- [Laravel Sail](https://laravel.com/docs/sail)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) + npm

---

## Development Setup

```bash
# 1. Clone the repository
git clone https://github.com/ant-garz/dash-data.git
cd dash-data

# 2. Install Laravel dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Start Sail (this builds containers on first run)
./vendor/bin/sail up -d

# 5. Generate app key
./vendor/bin/sail artisan key:generate

# 6. Run migrations
./vendor/bin/sail artisan migrate

# 7. Install frontend dependencies
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

### please note
You can also use docker desktop to open a terminal into the application directly and not use ./vendor/bin/sail


## 🔐 HTTPS & Security Notice

This project is developed using HTTP for local development and testing purposes only.

**⚠️ Important: This project is not configured for HTTPS by default.**

- All API traffic in development runs over `http://localhost`.
- Tokens (such as Bearer tokens for authentication) are transmitted in plaintext during local development.
- This setup is intended for **personal, educational, and portfolio use only**, not for deployment in a production environment.

### Recommended for Production Use

If deploying this project publicly, ensure the following:

- Use **HTTPS** with a valid SSL certificate to prevent token leakage and man-in-the-middle attacks.
- Ensure proper CORS configuration and CSRF protection for stateful apps.
- Secure `.env` files and database credentials before deploying.

This project prioritizes functionality and developer learning over hardened production security by default.

### If someone wants to use HTTPS locally
- valet secure my-app   # If using Laravel Valet
- use mkcert for local SSL certs

# **⚠️ Production Warning**

 If deploying this project publicly, make sure to:

 - ✅ Use **HTTPS** with a valid SSL certificate to protect user data and authentication tokens.
 - ✅ Secure all environment variables (`.env`) and **never commit them** to version control.
 - ✅ Configure **CORS** properly to control cross-origin access to the API.
 - ✅ Enable **CSRF protection** if your frontend makes state-changing requests (e.g., POST, PUT, DELETE).
 - ✅ Sanitize and validate **uploaded video files** to restrict file types (e.g., `.mp4`, `.mov`) and enforce size limits.
 - ✅ Keep **Laravel and dependencies updated** to receive the latest security patches.
 - ✅ Limit database access with least-privilege user roles and secure credentials.
 - ✅ Run `APP_DEBUG=false` and log errors in a non-sensitive way (e.g., Sentry, Laravel logs).