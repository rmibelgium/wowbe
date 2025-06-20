# WOW-BE Installation Guide

## OAuth configuration

### Google

Documentation: <https://developers.google.com/identity/oauth2/web/guides/get-google-api-clientid>

1. Go to the Google Console: <https://console.cloud.google.com/>
1. Create a new project: <https://console.cloud.google.com/projectcreate>
1. Go to <https://console.cloud.google.com/apis/credentials>
1. Click on "Create Credentials" and select "OAuth client ID".
1. Select "Web application" as the application type.
1. Add the following authorized redirect URIs:
   - `http://localhost:8000/auth/google/callback`
   - `https://your-domain.com/auth/google/callback`
1. Click "Create" and copy the Client ID and Client Secret.

### GitHub

Documentation: <https://docs.github.com/en/developers/apps/building-oauth-apps/creating-an-oauth-app>

1. Go to the your Developer settings: <https://github.com/settings/developers>
1. Click on "New OAuth App".
1. Fill in the application name, homepage URL, and description.
1. Set the authorization callback URL to:
   - `http://localhost:8000/auth/github/callback`
   - `https://your-domain.com/auth/github/callback`
1. Click "Register application" and copy the Client ID and Client Secret.

### Apple

Documentation: <https://developer.apple.com/documentation/signinwithapple>

1. Enroll to Apple Developer Program: <https://developer.apple.com/programs/enroll/> (99 EUR per year).
1. Go to the Apple Developer Console: <https://developer.apple.com/account/resources/>.
1. ...

Useful link: <https://developer.okta.com/blog/2019/06/04/what-the-heck-is-sign-in-with-apple>


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

Copy the `.env.docker` file to `.env` and set your environment variables accordingly.

Then run the following command to build and run the Docker container:

```bash
docker build --tag wow-be --label "project=wow-be" .
docker run --publish 8000:8000 --env-file .env --detach wow-be
```

#### Use Docker Compose

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
