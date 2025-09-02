<?php

namespace AwsSync;

require_once __DIR__.'/../vendor/autoload.php';

use DateTime;
use DateTimeZone;
use Dotenv\Dotenv;
use Phar;
use RuntimeException;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
// use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Throwable;

if (Phar::running() !== '') {
    $path = dirname(Phar::running(false));
} else {
    $path = __DIR__.'/../dist';
}

try {
    $composerJson = @file_get_contents("$path/../composer.json");
    if ($composerJson === false) {
        throw new RuntimeException('Could not read "composer.json" file');
    }
    $composer = json_decode($composerJson, true, 512, JSON_THROW_ON_ERROR);

    $dotenv = Dotenv::createImmutable($path);
    $dotenv->load();
    $dotenv->required(['AWS_HOST', 'AWS_PORT', 'AWS_USERNAME', 'AWS_PASSWORD', 'AWS_DBNAME', 'WOW_HOST', 'WOW_PORT', 'WOW_USERNAME', 'WOW_PASSWORD', 'WOW_DBNAME'])->notEmpty();
} catch (Throwable $e) {
    echo "\033[7;31m{$e->getMessage()}\033[0m\n";
    exit(Command::INVALID);
}

(new SingleCommandApplication)
    ->setName($composer['name'])
    ->setDescription($composer['description'])
    ->setVersion($composer['version'])
    ->setCode(function (
        OutputInterface $output,
        #[Option(description: 'Start date for synchronization (local time)')] ?string $from = null,
        #[Option(description: 'End date for synchronization (local time)')] ?string $to = null,
        #[Option(description: 'Enable debug mode', shortcut: 'd')] bool $debug = false
    ): int {
        // Display title
        $output->writeln('<bg=yellow;options=bold>AWS to WOW database synchronization</>');
        $output->writeln('<bg=yellow>Synchronizing AWS stations</>');
        if (! is_null($from) && ! is_null($to)) {
            $datetimeFrom = new DateTime($from, new DateTimeZone('Europe/Brussels'));
            $datetimeTo = new DateTime($to, new DateTimeZone('Europe/Brussels'));
            $output->writeln(sprintf('<bg=yellow>Synchronizing AWS observations from %s to %s</>', $datetimeFrom->format(DateTime::ATOM), $datetimeTo->format(DateTime::ATOM)));
        }

        // Connect to Oracle AWS database
        $output->writeln('Connecting to Oracle AWS database...');
        $aws = new Oracle(
            $_ENV['AWS_HOST'],
            $_ENV['AWS_PORT'],
            $_ENV['AWS_DBNAME'],
            $_ENV['AWS_USERNAME'],
            $_ENV['AWS_PASSWORD']
        );
        $output->writeln('<fg=green>Connected to Oracle AWS database!</>');

        // Get AWS stations
        $awsStations = $aws->getStations($debug, $output);
        $output->writeln(sprintf('<fg=yellow>AWS (active): %d</>', count($awsStations)));

        // $table = new Table($output);
        // $table
        //     ->setColumnMaxWidth(5, 75)
        //     ->setHeaders(['STAT_ID', 'NAME', 'LATITUDE', 'LONGITUDE', 'ALTITUDE'])
        //     ->setRows($awsStations)
        // ;
        // $table->render();

        // Get AWS observations
        if (! is_null($from) && isset($datetimeFrom) && ! is_null($to) && isset($datetimeTo)) {
            $awsObservations10Min = $aws->getAWS10Min($datetimeFrom, $datetimeTo, $debug, $output);
            $output->writeln(sprintf('<fg=yellow>AWS 10 min. Observations: %d</>', count($awsObservations10Min)));
        }

        // $table = new Table($output);
        // $table
        //     ->setHeaders(['STAT_ID', 'DATETIME', 'TEMPERATURE', 'PRESSURE', 'HUMIDITY', 'PRECIPITATION', 'WIND_SPEED', 'WIND_DIRECTION'])
        //     ->setRows($awsObservations10Min)
        // ;
        // $table->render();

        // Connect to PostgreSQL WOW database
        $output->writeln('Connecting to PostgreSQL WOW database...');
        $wow = new PostgreSQL(
            $_ENV['WOW_HOST'],
            $_ENV['WOW_PORT'],
            $_ENV['WOW_DBNAME'],
            $_ENV['WOW_USERNAME'],
            $_ENV['WOW_PASSWORD']
        );
        $output->writeln('<fg=green>Connected to PostgreSQL WOW database!</>');

        // Import stations
        $output->writeln('Processing stations...');
        $wow->syncStations($awsStations, $debug, $output);

        // Import observations
        if (! is_null($from) && isset($datetimeFrom) && ! is_null($to) && isset($datetimeTo) && isset($awsObservations10Min)) {
            $output->writeln('Processing observations...');
            $wow->syncObservations($awsObservations10Min, $datetimeFrom, $datetimeTo, $debug, $output);
        }

        return Command::SUCCESS;
    })
    ->run();
