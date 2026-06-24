# TruLuv — Laravel dating-site proof of concept

A proof of concept for a simple dating-style website: a marketing landing page,
registration/login, a dating profile, browsing other profiles, and one-to-one
messaging. It pairs a clean database design and idiomatic Laravel structure with
a polished, dark-themed Vue frontend, and runs locally with a single command.

## Stack

| Layer        | Choice | Why |
|--------------|--------|-----|
| Framework    | **Laravel 13** (PHP 8.4) | Current stable Laravel. |
| Front end    | **Vue 3 + Inertia + Vite + Tailwind 4** (official Vue starter kit) | First-party, widely-adopted way to do Vue inside a Laravel monolith — no separate API or SPA repo. |
| UI           | Custom **dark-mode** design with a small base-component library and composables (see below) | Pure Vue 3 Composition API + TypeScript. |
| Auth         | **Laravel Fortify** (from the starter kit) | Standard, battle-tested registration/login/logout. Not hand-rolled. |
| Database     | **MySQL 8** | Via Docker. Migrations + Eloquent throughout. |
| Tests        | **Pest** | The modern default for new Laravel apps. |

### Frontend

The UI is dark-first and built from a small, reusable base-component library
(`resources/js/components/base/`) — `BaseButton`, `BaseInput`, `BaseTextarea`,
`BaseBadge`, `BaseAvatar`, `BaseCard`, `StatCounter`, etc. — composed into the
pages. Two composables drive the motion: `useInView` (IntersectionObserver-based
scroll reveal) and `useCountUp` (animated stats). Everything is pure Vue 3
Composition API with TypeScript. The public landing page at `/` features seeded
members, animated stats, how-it-works, and success stories.

> Demo member photos are loaded from the free, gender-tagged **xsgames.co**
> avatar set (so a profile's photo matches its gender), which means
> the browse/landing pages need an internet connection to show faces (names and
> data come from the local database).

## Requirements

Just **Docker** (with the Compose plugin). Nothing else is needed locally —
PHP, Composer, Node and MySQL all run inside the containers.

## Run it

```bash
docker compose up --build
```

The first run builds the image (Composer install + `npm run build`), waits for
MySQL, runs migrations, and seeds demo data. When you see
`Server running on [http://0.0.0.0:8000]`, open:

**http://localhost:8000**

To stop: `Ctrl+C`, then `docker compose down` (add `-v` to also wipe the
database volume and re-seed fresh on the next run).

### Demo login

The database is seeded with ~60 users (each with a profile, ~5 pages of browse)
and a few seeded conversations. Log in with:

- **Email:** `alice@example.com`
- **Password:** `password`

(All seeded users share the password `password`; Alice is the one with a known
email. Alice already has a few conversations in her inbox.)

## Run the tests

With the stack running:

```bash
docker compose exec app php artisan test
```

The suite runs against an in-memory SQLite database (configured in
`phpunit.xml`), so it is independent of the MySQL container.

## Features

- **Auth** — register, login, logout (Fortify). The sign-up form captures the
  user's **gender**, and a newly registered user is redirected to complete the
  rest of their profile (age, bio, photo).
- **Profile** — each user has a dating profile (`age`, `gender`, `photo`,
  `bio`). Users edit their own profile at **My profile** (gender is a select).
- **Browse** — a paginated (12/page) grid **or** list view of other users'
  profiles with gender icons (the current user is excluded; only completed
  profiles are shown).
- **Messaging** — start a one-to-one conversation from a profile, view the
  thread, and reply. The **Inbox** lists your conversations with a preview and
  marks unread chats. Opening a thread marks its messages **read**, and the
  header **Inbox badge** shows how many chats still have unread messages.

## Architecture & decisions

### Database schema

```
users            (Laravel/Fortify default: id, name, email, password, ...)
profiles         id, user_id (unique FK), age, gender, photo_url, bio, timestamps
                 (age/bio nullable: gender is set at sign-up, the rest later)
conversations    id, user_one_id (FK), user_two_id (FK), timestamps
                 UNIQUE (user_one_id, user_two_id)
messages         id, conversation_id (FK), sender_id (FK -> users), body,
                 read_at (nullable), timestamps
                 INDEX (conversation_id, created_at)
```

Relationships:

- `User hasOne Profile`, `Profile belongsTo User`
- `Conversation belongsTo userOne / userTwo (User)`, `hasMany Messages`,
  `hasOne latestMessage`
- `Message belongsTo Conversation`, `belongsTo sender (User)`
- `User hasMany sentMessages`

All foreign keys use `cascadeOnDelete`.

**Why a separate `profiles` table** (rather than adding columns to `users`):
it keeps authentication concerns separate from dating-profile data and makes
the one-to-one relationship explicit — better for demonstrating relationship
modelling, and a more natural place to grow profile fields later.

**Preventing duplicate conversations.** A conversation stores its two
participants in a *canonical order* (`user_one_id` = the lower id,
`user_two_id` = the higher id) with a `UNIQUE (user_one_id, user_two_id)`
constraint. `App\Services\ConversationService::findOrCreateBetween()` applies
that ordering and uses `firstOrCreate`, so it doesn't matter who starts the
conversation — the same pair always resolves to a single thread.

### Where logic lives

- **Validation** → Form Request classes (`ProfileUpdateRequest`,
  `StartConversationRequest`, `StoreMessageRequest`). Kept minimal per the
  brief (e.g. age 18–120; can't start a conversation with yourself).
- **Authorization** → `ConversationPolicy`. A user can only view a thread or
  post to it if they are one of the two participants; non-participants get a
  `403`. Enforced in the controllers and covered by tests.
- **Non-trivial logic** → `ConversationService` (find-or-create), not inline
  in controllers. Controllers stay thin.
- **No N+1** → the browse and inbox queries eager-load their relationships
  (`with(...)`, `withMax(...)`).

### Notes on the reference project (`micca`)

A reference Laravel project was provided. I adopted its conventions where they
match standard Laravel (Pint with the `laravel` preset; conventional app
structure) and deviated where the standard/simpler path was better for a PoC:

- **Pest** instead of micca's PHPUnit (Pest is the modern default for new apps).
- A **lean Docker setup** (`php artisan serve` + MySQL) instead of micca's
  Octane/Swoole/Redis/RabbitMQ stack.
- The starter kit's **Fortify** auth instead of micca's Keycloak SSO.
- **No proprietary packages** — only first-party / popular open-source ones.

### Deliberately left out (scope control)

No matching/swiping algorithm, no photo uploads, no real-time websockets, no
notifications, no admin panel. Email verification is enabled in Fortify but not
enforced (the `User` model does not implement `MustVerifyEmail`), which is
reasonable for a PoC.

## Possible extensions

- Matching / "likes" with mutual-match detection
- Photo uploads (avatars, galleries)
- Real-time messaging (Laravel Reverb / WebSockets) instead of page reloads
- Search & filtering on browse (age range, gender, etc.)
- Enforced email verification and richer profile fields

## AI disclosure

AI assistance (Claude) was used to scaffold and implement this proof of
concept — generating boilerplate, wiring up the starter kit, and speeding up
the repetitive parts. The architecture decisions (database schema and
relationships, the conversation-uniqueness approach, project structure, and
scope) were directed and reviewed by me, and the code and tests were verified
to run end-to-end in Docker.
