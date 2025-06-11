# WOW-BE Installation Guide

## Run locally

### Start the application

#### Use PHP Built-in Server

Copy the `.env.example` file to `.env` and set your environment variables accordingly.

Then run the following command to start the PHP built-in server:

```bash
php artisan serve
```

#### Use Laravel Octane

Copy the `.env.example` file to `.env` and set your environment variables accordingly.

Then run the following command to start the Laravel Octane server:

```bash
php artisan octane:frankenphp
```

#### Use Docker

> [!TIP]  
> Benefits of using this solution is that it includes PostgreSQL.

Set your environment variables accordingly in the `.env.docker` file.

Then run the following command to start the Docker containers:

```bash
docker compose up
```

### Finalize the installation

```bash
php artisan migrate
php artisan optimize
```

If you need some sample data, you can run:

```bash
php artisan db:seed
```
