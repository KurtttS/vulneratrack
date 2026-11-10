# Vulneratrack

Vulneratrack is a PHP web application for community flood-risk information and reporting. This guide helps contributors set it up locally before making changes.

## Before you start

Install:

- Git
- PHP 8.4 (the Docker image uses PHP 8.4) with `pdo_pgsql` and `curl` enabled
- Composer 2
- Access to a configured Supabase project for the database and report-attachment storage

Docker is an alternative to installing PHP locally; the included Dockerfile configures PHP and the upload limits. The application uses Supabase PostgreSQL, so a working Supabase project and its credentials are still needed.

## 1. Clone the repository

```sh
git clone https://github.com/KurtttS/vulneratrack.git
cd vulneratrack
```

If you are working on an existing fork, clone your fork's URL instead.

## 2. Create your local environment file

Copy `.env.example` to `.env` in the project root, then fill in the values from your Supabase project:

```text
SUPABASE_DB_HOST=
SUPABASE_DB_PORT=5432
SUPABASE_DB_NAME=postgres
SUPABASE_DB_USER=
SUPABASE_DB_PASSWORD=
SUPABASE_URL=https://<project-ref>.supabase.co
SUPABASE_SERVICE_ROLE_KEY=
SUPABASE_REPORT_BUCKET=report-uploads
GEMINI_API_KEY=
APP_ENV=local
```

Use your own development credentials. Do not commit `.env`, share the service-role key, or put secrets in browser code. `.env` is ignored by Git; `.env.example` is safe to commit because it contains placeholders.

## 3. Prepare the Supabase database

In the Supabase SQL Editor:

1. For a new project, run [`db/supabase_schema.sql`](db/supabase_schema.sql).
2. If the application's original schema is already installed, run only the deployment additions block documented in [`db/SUPABASE.md`](db/SUPABASE.md).
3. Keep the default private `report-uploads` bucket, or create the private bucket named by `SUPABASE_REPORT_BUCKET`.

The database connection uses SSL. Additional database and deployment notes are in [`db/SUPABASE.md`](db/SUPABASE.md).

## 4. Install PHP dependencies

Run this from the repository root:

```sh
composer install
```

This installs the dependencies recorded in `composer.lock`. You generally run it once after cloning, and again if `composer.json` or `composer.lock` changes—not before every push. The generated `vendor/` directory is local and is not committed.

## 5. Start the local site

With PHP installed, run the built-in server from the repository root:

```sh
php -S 127.0.0.1:8000 -t .
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000). Leave the terminal running while you use the site. The PHP installation needs `pdo_pgsql` and `curl`; report uploads also need `upload_max_filesize=50M` and `post_max_size=110M`. These upload limits are configured automatically by the Dockerfile. For other local PHP setups, set them in `php.ini` and restart the server.

## 6. Start a feature branch

Before editing, update your local main branch and create a branch for the feature or fix:

```sh
git switch main
git pull
git switch -c feature/short-description
```

If your default branch is named differently, replace `main` with that branch name. Keep changes focused, follow the patterns in nearby code, and do not include local credentials or generated dependencies.

## 7. Check and share your changes

Before committing:

```sh
git status
git diff --check
git diff
```

Run the relevant checks for the files you changed. For PHP files, you can check syntax with:

```sh
php -l path/to/changed-file.php
```

Commit and push your feature branch, then open a pull request:

```sh
git add path/to/changed-files
git commit -m "Describe the change"
git push -u origin feature/short-description
```

Review the staged diff before committing, especially to ensure `.env`, credentials, and unrelated changes are not included.
