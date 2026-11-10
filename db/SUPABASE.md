# Supabase PostgreSQL setup

1. Run [`supabase_schema.sql`](./supabase_schema.sql) in the Supabase SQL Editor.
2. Run `composer install` from the project root to install the `.env` loader.
3. Set these variables in the root `.env` file; it is loaded automatically. Never commit the database password:
   - `SUPABASE_DB_HOST`
   - `SUPABASE_DB_PORT` (optional; defaults to `5432`)
   - `SUPABASE_DB_NAME` (usually `postgres`)
   - `SUPABASE_DB_USER`
   - `SUPABASE_DB_PASSWORD`
4. Ensure PHP has the `pdo_pgsql` extension enabled. On XAMPP, uncomment `extension=pdo_pgsql` in `php.ini`, then restart Apache. Connections require SSL.

The schema file creates the application tables and seeds the three area records. It does not import data from the legacy MariaDB dump; migrate existing data separately, correcting MariaDB-only values such as `0000-00-00` dates before loading them into PostgreSQL.
