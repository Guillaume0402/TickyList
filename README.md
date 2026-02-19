# TickyList — Project-based Task Manager (PHP MVC + MySQL)

TickyList is a simple, portfolio-ready task manager where tasks are organized by projects, with due dates, priorities, and reminders.

## Features
- Authentication (register / login / logout) with PHP sessions
- Projects: create / edit / delete
- Tasks inside projects:
  - title (required), description (optional)
  - status: `todo` / `doing` / `done`
  - priority: 1..3
  - due date + reminder datetime
- Dashboard views: Today / Overdue / Upcoming
- Search + filters (project, status, priority)
- Soft delete + Trash (restore / permanent delete)
- Security:
  - CSRF protection on POST forms
  - Password hashing (`password_hash` / `password_verify`)
  - Prepared statements (PDO)

## Tech Stack
- PHP 8 (vanilla) — MVC + Front Controller router
- MySQL 8 (PDO)
- Docker (PHP Apache, MySQL, phpMyAdmin, Mailpit)
- Bootstrap 5 + Sass (compiled to `public/assets/app.css`)
- JavaScript (Fetch) for quick actions (e.g. status toggle)

## Quick Start (Docker)
### Requirements
- Docker + Docker Compose
- Node.js + npm

### Run
```bash
cp .env.example .env
make dev
```

App URLs (ports depend on `.env`):
- App: `http://localhost:${WEB_PORT}`
- phpMyAdmin: `http://localhost:${PMA_PORT}`
- Mailpit: `http://localhost:${MAILPIT_UI_PORT}`

## Database
The MySQL container is reachable from PHP with:
- host: `db`
- port: `3306`
- credentials in `.env`

SQL init scripts:
- `docker/mysql/initdb/` (executed on first volume creation)

## Project Structure (high level)
- `public/` — Front Controller (`index.php`) + assets
- `routes/` — route definitions
- `src/Controllers/` — controllers
- `src/Models/` — models (PDO)
- `src/Core/` — router, base controller, helpers
- `src/Views/` — views + layouts
- `assets/scss/` — Sass sources compiled to `public/assets/`

## Roadmap
- Activity history (task events)
- Reminder emails via CLI script + cron (optional)
- Project sharing (later)

## Screenshots
Add screenshots in `documents/` and link them here.

## License
MIT