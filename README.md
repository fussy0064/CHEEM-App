# CHEEM App

Consultation, Health & Environment Management portal. Connects field workers/site managers with Health Officers for reporting, requesting, and managing health/environment/safety services.

## Stack
- PHP 7.4+ / Yii2
- MySQL
- Apache2 (AWS EC2)

## Setup (on your EC2 server)

```bash
git clone https://github.com/fussy0064/CHEEM-App.git
cd CHEEM-App
composer install
```

1. Copy DB credentials into `config/db.php` (do NOT commit real credentials).
2. Set a real random string in `config/web.php` -> `cookieValidationKey`.
3. Create database, then run migrations:
   ```bash
   php yii migrate
   ```
4. Point Apache DocumentRoot to `/web` (see `deploy/apache-cheem.conf`).
5. `chmod -R 775 runtime web/assets web/uploads`

## Roles
- `field_worker` — reports issues, submits requests
- `health_officer` — manages Kanban board, resolves requests
- `admin` — full access (set manually in DB, not selectable at signup)

## Structure
- `models/User.php` — auth + roles
- `models/ServiceRequest.php` — reporting/requests
- `controllers/SiteController.php` — login/signup/dashboard
- `controllers/RequestController.php` — create/manage requests
- `migrations/` — DB schema

## Security notes
- Passwords hashed with Yii2 security component (bcrypt)
- Admin role cannot be self-assigned at signup
- File uploads: extension-restricted, random filenames, 5MB limit
- Role checks enforced in controller `access` rules (not just UI)
- Set `YII_DEBUG` to `false` and `YII_ENV` to `'prod'` in `web/index.php` before going live
