# Build CSS & JavaScript assets

FROM alpine:3.22 AS build

RUN apk add --no-cache nodejs npm php83 php83-dom php83-exif php83-fileinfo php83-iconv php83-mbstring php83-openssl php83-phar php83-session php83-sodium php83-tokenizer php83-xml php83-xmlwriter

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-progress
RUN npm install
RUN npm run build

# Application

FROM dunglas/frankenphp:php8.3-alpine AS app

RUN install-php-extensions exif intl pcntl pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY . .
COPY --from=build /app/public/build /app/public/build

RUN composer install --no-progress --no-dev --optimize-autoloader

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
