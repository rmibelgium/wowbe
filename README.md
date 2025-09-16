# WOW-BE Reboot

New version of the WOW-BE back-end and database.

This version is developed using [Laravel](https://laravel.com/) and is running inside a [Docker](https://www.docker.com/) container using [Laravel Octane](https://laravel.com/docs/12.x/octane) with [FrankenPHP](https://frankenphp.dev/).

## Installation

See [Installation Guide](INSTALL.md).

## External services

### Geocoding API

WOW-BE site registration form uses [Nominatim](https://nominatim.org/) for geocoding (see [usage policy](https://operations.osmfoundation.org/policies/nominatim/)).  
If needed, it can be incorporated via the [Docker container](https://github.com/mediagis/nominatim-docker).

### Elevation API

WOW-BE site registration form uses [Open Elevation](https://open-elevation.com/) for elevation data. Free usage is limited to 1000 requests/month.  
If needed, it can be incorporated via the [Docker container](https://github.com/Jorl17/open-elevation/blob/master/docs/host-your-own.md).

## Documentation

- API Version 2: <https://wow.meteo.be/docs/api/>
- API Version 1 (backward compatible): <https://wow.meteo.be/docs/api/v1/>

## Tests

Unit and feature tests are available (see [Laravel documentation](https://laravel.com/docs/12.x/testing) for more information).
