#################################
# Build CSS & JavaScript assets #
#################################

FROM alpine:3.22 AS build

# Install Node.js, PHP, and required extensions
RUN apk add --no-cache nodejs npm php83 php83-dom php83-exif php83-fileinfo php83-iconv php83-mbstring php83-openssl php83-phar php83-session php83-sodium php83-tokenizer php83-xml php83-xmlwriter

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Change working directory
WORKDIR /app

# Install Composer dependencies
COPY composer.json composer.lock .
RUN composer install --no-progress --no-scripts --no-autoloader

# Install Node.js dependencies
COPY package.json package-lock.json .
RUN npm install

# Copy application files
COPY . .

# Finalize Composer autoload
RUN composer dump-autoload --optimize && composer run-script post-autoload-dump

# Build CSS & JavaScript assets
RUN npm run build

###############
# Application #
###############

FROM dunglas/frankenphp:php8.3-alpine AS app

# Install required PHP extensions
RUN install-php-extensions exif intl pcntl pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Change working directory
WORKDIR /app

# Install Composer dependencies
COPY composer.json composer.lock .
RUN composer install --no-progress --no-dev --no-scripts --no-autoloader

# Copy application files
COPY . .
# Copy built assets from the build stage
COPY --from=build /app/public/build /app/public/build

# Finalize Composer autoload
RUN composer dump-autoload --optimize && composer run-script post-autoload-dump

# Change ownership to `nobody` user and group
RUN chown -R nobody:nobody /app

# Set capabilities for `frankenphp` binary to allow binding to low ports
# This is necessary for running on ports < 1024, such as port 80 and 443
RUN setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp;

# Run as `nobody` user
USER nobody

# Start the application
ENTRYPOINT ["php", "artisan", "octane:frankenphp"]

HEALTHCHECK --interval=10s --timeout=5s --retries=3 \
    CMD ["php", "artisan", "octane:status"]
