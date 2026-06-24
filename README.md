# TruLuv — Laravel dating-site proof of concept

A small dating-style website: landing page, auth, dating profiles, browse
(grid/list), and one-to-one messaging with read receipts. Built with a clean
database design and a dark-themed Vue frontend, runnable with one command.

**Stack:** Laravel 13 (PHP 8.4) · Vue 3 + Inertia + Vite + Tailwind 4 (official
Vue starter kit, Fortify auth) · MySQL 8 · Pest · Docker.

## Run it

Only **Docker** is required.

```bash
docker compose up --build
```

First run installs dependencies, builds assets, migrates, and seeds demo data.
When you see `Server running on [http://0.0.0.0:8000]`, open **http://localhost:8000**.

**Demo login:** `alice@example.com` / `password` (all seeded users use
`password`; Alice has conversations in her inbox).

## Tests

```bash
docker compose exec app php artisan test
```

Runs against in-memory SQLite (see `phpunit.xml`), independent of MySQL.

## Features

- **Auth** (Fortify) — login, register (with gender), forgot password. New users
  are sent to complete their profile.
- **Profile** — age, gender, photo, bio. Edited at *My profile*.
- **Browse** — paginated grid/list of other completed profiles, with gender icons.
- **Messaging** — one-to-one threads with an inbox, read receipts, and an unread
  badge on the inbox icon.

## Architecture notes

```
users         (Fortify default)
profiles      user_id (unique FK), age, gender, photo_url, bio
conversations user_one_id, user_two_id   — UNIQUE(user_one_id, user_two_id)
messages      conversation_id, sender_id, body, read_at
```

- **Separate `profiles` table** keeps auth data apart from dating data and makes
  the `User hasOne Profile` relationship explicit. FKs use `cascadeOnDelete`.
- **No duplicate conversations** — participants are stored in canonical order
  (lower id first) with a unique constraint; `ConversationService::findOrCreateBetween()`
  resolves any pair to a single thread.
- **Thin controllers** — validation in Form Requests, authorization in
  `ConversationPolicy` (non-participants get `403`), find-or-create logic in a
  service. Browse/inbox eager-load to avoid N+1.
- **Frontend** — pure Vue 3 Composition API + TypeScript, reusable base
  components (`resources/js/components/base/`) and composables. Demo photos load
  from the free `xsgames.co` avatar set (needs internet to display faces).

## AI disclosure

AI (Claude) was used to scaffold and speed up implementation. The architecture
(schema and relationships, conversation-uniqueness approach, structure, scope)
was directed and reviewed by me, and verified to run end-to-end in Docker.
