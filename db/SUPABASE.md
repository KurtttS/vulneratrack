# Supabase PostgreSQL setup

1. For a fresh database, run [`supabase_schema.sql`](./supabase_schema.sql) in the Supabase SQL Editor. If the original application schema is already installed, run only the block between `BEGIN VERCEL DEPLOYMENT ADDITIONS` and `END VERCEL DEPLOYMENT ADDITIONS` at the bottom of that file. That block is safe to rerun.
2. Copy the root `.env.example` to `.env` and fill in your credentials. On initial setup (or after `composer.json` or `composer.lock` changes), run `composer install` from the project root to install dependencies. You do not need to run it before every push.
3. Fill in the required values in `.env`; it is loaded automatically. Never commit `.env` or expose its secrets:
   - `SUPABASE_DB_HOST`
   - `SUPABASE_DB_PORT` (optional; defaults to `5432`)
   - `SUPABASE_DB_NAME` (usually `postgres`)
   - `SUPABASE_DB_USER`
   - `SUPABASE_DB_PASSWORD`
   - `SUPABASE_URL` (project URL, for example `https://<project-ref>.supabase.co`)
   - `SUPABASE_SERVICE_ROLE_KEY` (server-side secret key; never expose it in browser code)
   - `SUPABASE_REPORT_BUCKET` (optional; defaults to `report-uploads`)
   - `GEMINI_API_KEY` (required for the chatbot)
4. Ensure PHP has `pdo_pgsql` and `curl` enabled. Set `upload_max_filesize=50M` and `post_max_size=110M` to support report attachments. The Docker image configures these limits automatically; on XAMPP, set them in `php.ini` and restart Apache. Connections require SSL.
5. The schema additions create the private PostgreSQL session table and private `report-uploads` Storage bucket. If you set a custom `SUPABASE_REPORT_BUCKET`, create that private bucket in Supabase as well. New report attachments are uploaded to Storage and viewed through signed links. Existing files in `uploads/reports` are not copied automatically; old report-image references will need to be migrated if you need to keep them.

## Vercel deployment

The project is configured for Vercel's beta Container runtime using the root `Dockerfile` and `Caddyfile`; the existing PHP directory structure does not need to be rearranged. Deploy the repository as a Vercel project using the Container runtime, then add the database variables above plus `SUPABASE_URL`, `SUPABASE_SERVICE_ROLE_KEY`, and `GEMINI_API_KEY` under the project's environment variables. Set `APP_ENV=production`. Do not upload or commit `.env`; keep the service-role key server-side.

Apply the current schema in Supabase before deploying so PostgreSQL sessions and the private Storage bucket are ready. The container excludes local `uploads/`, so report files stored on disk are not included in deployments. This does not migrate historical attachments.

The sign-up birth-certificate field is not yet wired to persistent storage: its form currently omits multipart encoding and its handler reads `$_POST`. Do not rely on that upload during deployment; it needs a separate private Storage flow because these documents are sensitive.

The schema file creates the application tables and seeds the three area records. It does not import data from the legacy MariaDB dump; migrate existing data separately, correcting MariaDB-only values such as `0000-00-00` dates before loading them into PostgreSQL.
