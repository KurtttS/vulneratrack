FROM dunglas/frankenphp:1-php8.4-alpine AS base

WORKDIR /app
RUN install-php-extensions pdo_pgsql curl \
    && printf 'upload_max_filesize=50M\npost_max_size=110M\n' > /usr/local/etc/php/conf.d/uploads.ini

FROM base AS dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

FROM base AS runtime
COPY --from=dependencies /app/vendor ./vendor
COPY . .
COPY Caddyfile /etc/caddy/Caddyfile

ENV PORT=80
ENV APP_ENV=production

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
