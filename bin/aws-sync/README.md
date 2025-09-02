# WOW-BE AWS Synchronization script

```cmd
Description:
  Synchronize Automatic Weather Stations (AWS) and Observations from Oracle to PostgreSQL WOW database.

Usage:
  aws-sync.phar [options]

Options:
      --from=FROM       Start date for synchronization (local time)
      --to=TO           End date for synchronization (local time)
  -d, --debug           Enable debug mode
  -h, --help            Display help for the given command. When no command is given display help for the ./aws-sync.phar command
      --silent          Do not output any message
  -q, --quiet           Only errors are displayed. All other output is suppressed
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```