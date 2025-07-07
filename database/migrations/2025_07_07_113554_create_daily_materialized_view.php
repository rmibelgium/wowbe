<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE MATERIALIZED VIEW observations_day_agg AS
            WITH cleaned AS (
                SELECT
                    id,
                    site_id,
                    dateutc,
                    -- Temperature in Celsius if in range, else NULL
                    CASE
                        WHEN ((tempf - 32) / 1.8) BETWEEN -90 AND 60
                        THEN ROUND(((tempf - 32) / 1.8)::numeric, 1)
                    END AS temperature,
                    -- Dewpoint in Celsius if in range, else NULL
                    CASE
                        WHEN ((dewptf - 32) / 1.8) BETWEEN -100 AND 35
                        THEN ROUND(((dewptf - 32) / 1.8)::numeric, 1)
                    END AS dewpoint,
                    -- Humidity if in range, else NULL
                    CASE
                        WHEN humidity BETWEEN 0 AND 100
                        THEN humidity::numeric
                    END AS humidity,
                    -- Pressure in hPa if in range, else NULL
                    CASE
                        WHEN (1013.25 * (baromin / 29.92)) BETWEEN 870 AND 1100
                        THEN ROUND((1013.25 * (baromin / 29.92))::numeric, 1)
                    END AS pressure,
                    -- Wind speed in m/s if in range, else NULL
                    CASE
                        WHEN (windspeedmph * 1609.344 / 3600) BETWEEN 0 AND 120
                        THEN ROUND((windspeedmph * 1609.344 / 3600)::numeric, 1)
                    END AS windspeed,
                    -- Wind gust speed in m/s if in range, else NULL
                    CASE
                        WHEN (windgustmph * 1609.344 / 3600) BETWEEN 0 AND 120
                        THEN ROUND((windgustmph * 1609.344 / 3600)::numeric, 1)
                    END AS windgustspeed,
                    -- Wind direction if in range, else NULL
                    CASE
                        WHEN winddir BETWEEN 0 AND 360
                        THEN ROUND(winddir::numeric, 1)
                    END AS winddir,
                    -- Wind gust direction if in range, else NULL
                    CASE
                        WHEN windgustdir BETWEEN 0 AND 360
                        THEN ROUND(windgustdir::numeric, 1)
                    END AS windgustdir,
                    -- Visibility if in range, else NULL
                    CASE
                        WHEN visibility >= 0
                        THEN ROUND(visibility::numeric, 1)
                    END AS visibility,
                    -- Soil moisture if in range, else NULL
                    CASE
                        WHEN soilmoisture BETWEEN 0 AND 100
                        THEN soilmoisture::numeric
                    END AS soilmoisture,
                    -- Soil temperature in Celsius if in range, else NULL
                    CASE 
                        WHEN ((soiltempf - 32) / 1.8) BETWEEN -90 AND 60
                        THEN ROUND(((soiltempf - 32) / 1.8)::numeric, 1)
                    END AS soiltemperature,
                    -- Daily Rainin in mm if in range, else NULL
                    CASE
                        WHEN (dailyrainin * 25.4) BETWEEN 0 AND 300
                        THEN ROUND((dailyrainin * 25.4)::numeric, 1)
                    END AS dailyrainin
                FROM observations
            )
            SELECT
                site_id,
                DATE(dateutc AT TIME ZONE 'UTC' AT TIME ZONE 'Europe/Brussels') AS date,
                MIN(temperature) AS min_temperature,
                MAX(temperature) AS max_temperature,
                ROUND(AVG(temperature), 1) AS avg_temperature,
                ROUND(AVG(dewpoint), 1) AS avg_dewpoint,
                ROUND(AVG(humidity), 1) AS avg_humidity,
                ROUND(AVG(pressure), 1) AS avg_pressure,
                MAX(windspeed) AS max_windspeed,
                MAX(windgustspeed) AS max_windgustspeed,
                MAX(dailyrainin) AS max_dailyrainin
            FROM cleaned
            GROUP BY site_id, date;
        ");

        DB::statement('DROP MATERIALIZED VIEW IF EXISTS daily;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_day_agg;');

        DB::statement('CREATE MATERIALIZED VIEW daily AS 
            SELECT DATE(dateutc), site_id, 
                MIN(tempf) AS min_tempf, MAX(tempf) AS max_tempf, AVG(tempf) AS avg_tempf, 
                AVG(dewptf) AS avg_dewptf, 
                AVG(humidity) AS avg_humidity, 
                -1 AS max_rain, MAX(dailyrainin) AS max_dailyrainin, -1 AS duration_rain, 
                AVG(baromin) AS avg_baromin, 
                MAX(windspeedmph) AS max_windspeedmph, 
                MAX(windgustmph) AS max_windgustmph 
            FROM observations 
            GROUP BY 1, 2 
            ORDER BY 1, 2
            WITH DATA');
    }
};
