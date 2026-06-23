# Notes for the reviewer

Thanks for the test task — here's a short summary.

**What I built.** A Laravel 13 proof of concept for a simple dating site:
Fortify-based registration/login, a dating profile (name, age, bio, optional
gender), a paginated browse view of other users, and one-to-one
conversations/messaging. The focus, as you asked, is on the backend: a clean
normalized schema with proper Eloquent relationships, thin controllers with
validation in Form Requests, a small service for the find-or-create
conversation logic, and a policy so users can only access their own threads.

On top of that solid backend I also built a polished, dark-themed frontend: a
public TruLuv landing page (hero, animated stats, featured members, success
stories) and a small reusable base-component library plus composables, all in
pure Vue 3 Composition API + TypeScript with scroll animations and transitions.

**Stack.** Laravel 13 (PHP 8.4) with the official Vue + Inertia starter kit,
MySQL 8, and Pest for tests. I kept the dependency list lean and used only
first-party / popular open-source packages.

**Running it.** Everything is Dockerized — you only need Docker:

```bash
docker compose up --build
```

Then open http://localhost:8000 and log in with **alice@example.com** /
**password** (the database is auto-migrated and seeded with demo users and
conversations on first run). Tests: `docker compose exec app php artisan test`.
More detail, the schema, and my design decisions are in `README.md`.

**AI usage (as requested).** I used AI (Claude) to accelerate the build —
scaffolding, boilerplate, and the repetitive parts. The design decisions (the
database schema and relationships, the conversation-uniqueness approach, the
project structure, and scope) are mine, I reviewed everything, and I verified
the whole flow runs end-to-end in Docker. Happy to walk through any part of it
or extend it live on a call.
