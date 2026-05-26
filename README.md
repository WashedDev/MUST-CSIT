# MUST CSIT Society — Laravel App

Landing page + Auth (Login/Register) + post-auth pages (Dashboard, Events, Profile) for the
Malawi University of Science and Technology — Computer Science & IT Society.
Colors and layout inspired by https://www.must.ac.mw/ (deep green + gold).

## Setup

```bash
composer create-project laravel/laravel must-csit-base
# then copy these files INTO must-csit-base/, overwriting as needed
cd must-csit-base
cp .env.example .env
php artisan key:generate
touch database/database.sqlite          # using sqlite for simplicity
php artisan migrate
php artisan serve
```

The `.env.example` here is preconfigured for SQLite. Swap to MySQL by editing `DB_*` vars.

## Logo placeholders

Drop your real logos into:
- `public/images/must-logo.png`     (university crest)
- `public/images/csit-logo.png`     (society logo)

Placeholders are shown until you replace them.

## Routes

- `/`           landing page (public)
- `/login`      login (public)
- `/register`   register (public)
- `/dashboard`  member dashboard (auth)
- `/events`     events list (auth)
- `/profile`    profile (auth)
- `POST /logout`
