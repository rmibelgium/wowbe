<?php

namespace _HumbugBox4c97043d9cc9\AwsSync;

use DateTime;
use DateTimeZone;
use PDO;
use _HumbugBox4c97043d9cc9\Ramsey\Uuid\Uuid;
use _HumbugBox4c97043d9cc9\Symfony\Component\Console\Output\OutputInterface;
class PostgreSQL
{
    const DEFAULT_USER_EMAIL = 'labo@meteo.be';
    const LOCAL_TIMEZONE = 'Europe/Brussels';
    private ?PDO $connection;
    public function __construct(
        string $host,
        string $port,
        string $dbname,
        string $user,
        #[\SensitiveParameter]
        string $password
    )
    {
        $this->connection = new PDO("pgsql:host={$host};port={$port};dbname={$dbname}", $user, $password);
    }
    private function getUserID(): string
    {
        $stmt = $this->connection->prepare(<<<'SQL'
    SELECT id FROM users WHERE email = :email
SQL
);
        $stmt->execute([':email' => self::DEFAULT_USER_EMAIL]);
        $userID = $stmt->fetchColumn();
        if ($userID === \false) {
            throw new \RuntimeException('User ' . self::DEFAULT_USER_EMAIL . ' not found in WOW database. Please create this user first.');
        }
        return $userID;
    }
    private function getStationMatch(): array
    {
        $stmt = $this->connection->query(<<<'SQL'
    SELECT aws_stat_id, wow_site_id
    FROM aws_stations
SQL
);
        return $stmt !== \false ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }
    public function syncStations(array $stations, bool $debug = \false, ?OutputInterface $output = null): void
    {
        $countInserted = 0;
        $countUpdated = 0;
        try {
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->beginTransaction();
            $this->connection->exec(<<<'SQL'
    CREATE TABLE IF NOT EXISTS aws_stations (
        aws_stat_id SERIAL UNIQUE NOT NULL,
        wow_site_id UUID UNIQUE NOT NULL,
        PRIMARY KEY (aws_stat_id, wow_site_id),
        FOREIGN KEY (wow_site_id) REFERENCES sites (id)
    );
SQL
);
            $output?->writeln('<fg=green>Table `aws_stations` created in PostgreSQL WOW database!</>');
            $wowSites = $this->getStationMatch();
            $alreadyExistingAwsStations = array_column($wowSites, 'aws_stat_id');
            $output->writeln(sprintf('<fg=gray>There are already %d AWS in PostgreSQL WOW database!</>', count($alreadyExistingAwsStations)));
            foreach ($stations as $station) {
                if (!in_array($station['STAT_ID'], $alreadyExistingAwsStations)) {
                    $uuid = Uuid::uuid7();
                    $shortID = hash('xxh32', $uuid->toString());
                    $this->executeQuery(<<<'SQL'
    INSERT INTO sites (id, created_at, updated_at, name, timezone, auth_key, longitude, latitude, altitude, user_id, is_official, short_id)
    VALUES (:id, :created_at, :updated_at, :name, :timezone, :auth_key, :longitude, :latitude, :altitude, :user_id, :is_official, :short_id)
    RETURNING id
SQL
, [':id' => $uuid->toString(), ':created_at' => $station['DATE_BEGIN'], ':updated_at' => date('Y-m-d H:i:s'), ':name' => $station['NAME'], ':timezone' => self::LOCAL_TIMEZONE, ':auth_key' => bin2hex((new \Random\Randomizer(new \Random\Engine\Secure()))->getBytes(32)), ':longitude' => $station['LONGITUDE_WGS84'], ':latitude' => $station['LATITUDE_WGS84'], ':altitude' => round($station['ALTITUDE']), ':user_id' => $this->getUserID(), ':is_official' => \true, ':short_id' => $shortID], $debug ? $output : null);
                    $this->executeQuery(<<<'SQL'
    INSERT INTO aws_stations (aws_stat_id, wow_site_id) VALUES (:aws_stat_id, :wow_site_id)
SQL
, [':aws_stat_id' => $station['STAT_ID'], ':wow_site_id' => $uuid], $debug ? $output : null);
                    $countInserted++;
                } else {
                    $index = array_search($station['STAT_ID'], $wowSites);
                    $siteID = $wowSites[$index]['wow_site_id'];
                    $this->executeQuery(<<<'SQL'
    UPDATE sites SET name = :name, longitude = :longitude, latitude = :latitude, altitude = :altitude, updated_at = :updated_at WHERE id = :id
SQL
, [':id' => $siteID, ':updated_at' => date('Y-m-d H:i:s'), ':name' => $station['NAME'], ':longitude' => $station['LONGITUDE_WGS84'], ':latitude' => $station['LATITUDE_WGS84'], ':altitude' => !is_null($station['ALTITUDE']) ? round($station['ALTITUDE']) : -999], $debug ? $output : null);
                    $countUpdated++;
                }
            }
            $this->connection->commit();
            $output->writeln('<fg=green>AWS synchronization completed successfully!</>');
            $output->writeln("<fg=gray>{$countInserted} inserted AWS & {$countUpdated} updated AWS</>");
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            throw new \RuntimeException('PostgreSQL error: ' . $e->getMessage());
        }
    }
    public function syncObservations(array $observations, DateTime $from, DateTime $to, bool $debug = \false, ?OutputInterface $output = null): void
    {
        try {
            $wowSites = $this->getStationMatch();
            $statIDList = array_unique(array_column($observations, 'STAT_ID'));
            $siteIDList = array_column(array_filter($wowSites, fn($site) => in_array($site['aws_stat_id'], $statIDList)), 'wow_site_id');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->beginTransaction();
            $this->executeQuery(<<<'SQL'
    DELETE FROM observations_agg_5min
    WHERE site_id = ANY(:site_id_list)
    AND dateutc >= :from
    AND dateutc <= :to
SQL
, [':site_id_list' => '{' . implode(',', array_map(fn($id) => '"' . $id . '"', $siteIDList)) . '}', ':from' => (clone $from)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'), ':to' => (clone $to)->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s')], $debug ? $output : null);
            $this->executeQuery(<<<'SQL'
    DELETE FROM observations_agg_5min_local
    WHERE site_id = ANY(:site_id_list)
    AND datelocal >= :from
    AND datelocal <= :to
SQL
, [':site_id_list' => '{' . implode(',', array_map(fn($id) => '"' . $id . '"', $siteIDList)) . '}', ':from' => $from->format('Y-m-d H:i:s'), ':to' => $to->format('Y-m-d H:i:s')], $debug ? $output : null);
            foreach ($observations as $observation) {
                $index = array_search($observation['STAT_ID'], array_column($wowSites, 'aws_stat_id'));
                $siteID = $wowSites[$index]['wow_site_id'];
                $this->executeQuery(<<<'SQL'
    INSERT INTO observations_agg_5min (site_id, dateutc, temperature, pressure, humidity, rainin, windspeed, winddir, count)
    VALUES (:site_id, :dateutc, :temperature, :pressure, :humidity, :rainin, :windspeed, :winddir, :count)
SQL
, [':site_id' => $siteID, ':dateutc' => (new DateTime($observation['DATETIME'], new DateTimeZone(self::LOCAL_TIMEZONE)))->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s'), ':temperature' => $observation['TEMP_DRY_SHELTER_AVG'], ':pressure' => $observation['PRESSURE'], ':humidity' => $observation['HUMIDITY_REL_SHELTER_AVG'], ':rainin' => $observation['PRECIP_QUANTITY'], ':windspeed' => $observation['WIND_SPEED_10M'], ':winddir' => $observation['WIND_DIRECTION'], ':count' => 1], $debug ? $output : null);
                $this->executeQuery(<<<'SQL'
    INSERT INTO observations_agg_5min_local (site_id, datelocal, temperature, pressure, humidity, rainin, windspeed, winddir, count)
    VALUES (:site_id, :datelocal, :temperature, :pressure, :humidity, :rainin, :windspeed, :winddir, :count)
SQL
, [':site_id' => $siteID, ':datelocal' => $observation['DATETIME'], ':temperature' => $observation['TEMP_DRY_SHELTER_AVG'], ':pressure' => $observation['PRESSURE'], ':humidity' => $observation['HUMIDITY_REL_SHELTER_AVG'], ':rainin' => $observation['PRECIP_QUANTITY'], ':windspeed' => $observation['WIND_SPEED_10M'], ':winddir' => $observation['WIND_DIRECTION'], ':count' => 1], $debug ? $output : null);
            }
            $this->connection->commit();
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            throw new \RuntimeException('PostgreSQL error: ' . $e->getMessage());
        }
    }
    public function __destruct()
    {
        $this->connection = null;
    }
    private function executeQuery(string $sql, array $params, ?OutputInterface $output = null): void
    {
        $debug = sprintf("<fg=cyan>[DEBUG] SQL: %s</>\n", trim($sql));
        foreach ($params as $key => $value) {
            $debug .= sprintf("<fg=cyan>[DEBUG]  %s = %s</>\n", $key, is_null($value) ? '<fg=cyan;options=underscore>NULL</>' : $value);
        }
        $output?->writeln($debug);
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
    }
}
