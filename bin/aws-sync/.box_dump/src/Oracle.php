<?php

namespace _HumbugBox4c97043d9cc9\AwsSync;

use DateTime;
use PDO;
use _HumbugBox4c97043d9cc9\Symfony\Component\Console\Output\OutputInterface;
class Oracle
{
    private ?PDO $connection;
    public function __construct(
        string $host,
        string $port,
        string $dbname,
        string $username,
        #[\SensitiveParameter]
        string $password
    )
    {
        $this->connection = new PDO("oci:dbname=//{$host}:{$port}/{$dbname}", $username, $password);
    }
    public function getStations(bool $debug = \false, ?OutputInterface $output = null): array
    {
        return $this->query(<<<'SQL'
    SELECT 
        S.STAT_ID, S.DATE_BEGIN,
        MS.NAME,
        ML.LATITUDE_WGS84, ML.LONGITUDE_WGS84, ML.ALTITUDE
    FROM CLIMAT.AWS_STATION S 
        JOIN METADATA.STATION MS ON S.STAT_ID = MS.STAT_ID 
        JOIN METADATA.LOCATION ML ON S.STAT_ID = ML.STAT_ID 
    WHERE 
        S.DATE_END IS NULL
    ORDER BY S.STAT_ID ASC
SQL
, [], $debug ? $output : null);
    }
    public function getAWS10Min(DateTime $from, DateTime $to, bool $debug = \false, ?OutputInterface $output = null): array
    {
        return $this->query(<<<'SQL'
    SELECT
        S.STAT_ID,
        TO_CHAR(O.TIMESTAMP, 'YYYY-MM-DD HH24:MI:SS') AS DATETIME,
        O.TEMP_DRY_SHELTER_AVG,
        O.PRESSURE,
        O.HUMIDITY_REL_SHELTER_AVG,
        O.PRECIP_QUANTITY,
        NVL(O.WIND_SPEED_AVG_10M, O.WIND_SPEED_10M) AS WIND_SPEED_10M,
        O.WIND_DIRECTION
    FROM CLIMAT.AWS_10MIN O 
        JOIN CLIMAT.AWS_STATION S ON O.CODE = S.CODE 
    WHERE 
        O.TIMESTAMP >= TO_TIMESTAMP(:from_param, 'YYYY-MM-DD HH24:MI:SS') 
        AND O.TIMESTAMP <= TO_TIMESTAMP(:to_param, 'YYYY-MM-DD HH24:MI:SS')
        AND S.DATE_END IS NULL
    ORDER BY S.STAT_ID ASC
SQL
, [':from_param' => $from->format('Y-m-d H:i:s'), ':to_param' => $to->format('Y-m-d H:i:s')], $debug ? $output : null);
    }
    public function __destruct()
    {
        $this->connection = null;
    }
    private function query(string $sql, array $params, ?OutputInterface $output = null): array
    {
        $debug = sprintf("<fg=cyan>[DEBUG] SQL: %s</>\n", trim($sql));
        foreach ($params as $key => $value) {
            $debug .= sprintf("<fg=cyan>[DEBUG]  %s = %s</>\n", $key, is_null($value) ? '<fg=cyan;options=underscore>NULL</>' : $value);
        }
        $output?->writeln($debug);
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
