# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

Everything runs inside the `app` Docker container. The `Makefile` targets wrap `docker-compose exec app ...` (with `sudo`):

```bash
make start              # docker-compose up -d
make stop               # docker-compose down
make composer-install
make migrate            # also: make migrate:install, make migrate:rollback
make db:seed
make test               # vendor/bin/phpunit tests
make sh                 # shell into the app container
```

Running a single test or a suite (no Make target — exec directly):

```bash
sudo docker-compose exec app vendor/bin/phpunit --filter test_can_store_a_new_step_with_step_status_history
sudo docker-compose exec app vendor/bin/phpunit --testsuite Feature
```

Tests run against sqlite `:memory:` (set in `phpunit.xml`), not the MySQL container. Feature tests use `RefreshDatabase`.

Code style: `laravel/pint` is a dev dependency — `sudo docker-compose exec app vendor/bin/pint`.

Ports: app at http://localhost:8000/timeline, phpMyAdmin at http://localhost:9000, MySQL at 3306.

## Architecture

Laravel 10 / PHP 8.1 app tracking candidate recruitment journeys. No frontend build step — there is no `package.json`; Bootstrap 5 comes from CDN and all CSS/JS lives inline in Blade templates. `resources/js` and `resources/css` are unused scaffolding.

### Domain model

Three tables, strictly nested: `Timeline` hasMany `Step` hasMany `StepStatusHistory` (table name `step_status_history`, singular — set explicitly on the model). Status is never updated in place; a status change appends a new history row, and current status is derived:

- `Step::getCurrentStatusAttribute()` — latest history row's `status_category`, exposed via `$appends`, so `current_status` is always present on serialized steps.
- `Timeline::latestStepCategory()` / `stepCategories()` — derived from the steps relation.

Category values are PHP enums (`App\Enums\StepCategory`, `App\Enums\StatusCategory`) backed by human-readable strings ("First Interview", "Pending"). `EnumTrait` gives `values()` (used by migrations to define the DB `enum` columns) and `toArray()` (`[{id, title}]` shape consumed by Blade selects and seeders). **Adding an enum case requires a migration** — the column type is generated from `Enum::values()` at migration time.

### Business rules

These live in private `checkRestrictions()` methods on the controllers, returning either `false` or a 422 `JsonResponse` that the action short-circuits on. They are not in models, form requests, or policies:

- `StepController` — max 3 steps per timeline; no duplicate `step_category` within a timeline.
- `StepStatusHistoryController` — a status can only be changed away from `Pending`; once `Complete` or `Reject` exists in the history, further changes are rejected.
- `TimelineController::store()` — creating a timeline auto-creates a `First Interview` step in `Pending` (`createFirstStep()`).

### Routing split

The same controllers serve both surfaces:

- `routes/web.php` — HTML: `GET/POST /timeline`, `GET /timeline/new`, `GET /step/new/{timeline_id}`, `POST /step/{timeline_id}`. These actions `session()->flash('success', ...)` and redirect to `route('home')`.
- `routes/api.php` — JSON: `GET /api/timeline/{id}`, `POST /api/timeline`, `POST /api/step/{timeline_id}`, `POST /api/step-status-history` (named `status.store`). Feature tests exercise the API routes.

Because `status.store` is an API route called by JS in `index.blade.php` via `XMLHttpRequest`, the Blade page injects an `X-CSRF-TOKEN` header on it.

Resources live in `App\Resources` (not the usual `App\Http\Resources`) and are returned only from the JSON-shaped actions (`TimelineController::show`, `StepStatusHistoryController::store`).

### Views

`resources/views/layouts/app.blade.php` holds the design tokens (`:root` CSS variables) and flashes `session('success')` / `$errors`. Pages extend it and fill `@section('title'|'styles'|'content'|'scripts')`. `index.blade.php` is the main screen: accordion timeline cards whose status `<select>` elements POST to `status.store` and then mutate the DOM (circle colors, status dot, "+ Next step" button) optimistically without a reload. Some comments in that script are in Greek.
