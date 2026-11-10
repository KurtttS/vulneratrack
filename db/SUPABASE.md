# Supabase PostgreSQL setup

1. Run [`supabase_schema.sql`](./supabase_schema.sql) in the Supabase SQL Editor.
2. Set these environment variables for the PHP application; do not commit the database password:
   - `SUPABASE_DB_HOST`
   - `SUPABASE_DB_PORT` (optional; defaults to `5432`)
   - `SUPABASE_DB_NAME` (usually `postgres`)
   - `SUPABASE_DB_USER`
   - `SUPABASE_DB_PASSWORD`
3. Ensure PHP has the `pdo_pgsql` extension enabled. Connections require SSL.

The schema file creates the application tables and seeds the three area records. It does not import data from the legacy MariaDB dump; migrate existing data separately, correcting MariaDB-only values such as `0000-00-00` dates before loading them into PostgreSQL.
