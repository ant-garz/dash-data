# dash-data
Application for dash camera video and associated data

# Tech Stack
- Docker
- MySql
- [Laravel](https://laravel.com/docs)
- Inertia
- Svelte
- Bootstrap (via sveltestrap)


## 🔐 HTTPS & Security Notice

This project is developed using HTTP for local development and testing purposes only.

**⚠️ Important: This project is not configured for HTTPS by default.**

- All API traffic in development runs over `http://localhost`.
- Tokens (such as Bearer tokens for authentication) are transmitted in plaintext during local development.
- This setup is intended for **personal, educational, and portfolio use only**, not for deployment in a production environment.

### Recommended for Production Use

If deploying this project publicly, ensure the following:

- Use **HTTPS** with a valid SSL certificate to prevent token leakage and man-in-the-middle attacks.
- Set `SANCTUM_STATEFUL_DOMAINS` and `SESSION_DOMAIN` appropriately if switching to cookie-based auth.
- Ensure proper CORS configuration and CSRF protection for stateful apps.
- Secure `.env` files and database credentials before deploying.

This project prioritizes functionality and developer learning over hardened production security by default.

### If someone wants to use HTTPS locally
valet secure my-app   # If using Laravel Valet
# or use mkcert for local SSL certs