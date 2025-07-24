CREATE MATERIALIZED VIEW observations_day_agg_local AS
    WITH cleaned AS (
        SELECT
            o.id,
            o.site_id,
            o.dateutc AT TIME ZONE 'UTC' AT TIME ZONE s.timezone AS datelocal,
            -- Temperature in Celsius if in range, else NULL
            CASE
                WHEN ((tempf - 32) / 1.8) BETWEEN -90 AND 60
                THEN ((tempf - 32) / 1.8)::numeric
            END AS temperature,
            -- Dewpoint in Celsius if in range, else NULL
            CASE
                WHEN ((dewptf - 32) / 1.8) BETWEEN -100 AND 35
                THEN ((dewptf - 32) / 1.8)::numeric
            END AS dewpoint,
            -- Humidity if in range, else NULL
            CASE
                WHEN humidity BETWEEN 0 AND 100
                THEN humidity::numeric
            END AS humidity,
            -- Pressure in hPa if in range, else NULL
            CASE
                WHEN (1013.25 * (baromin / 29.92)) BETWEEN 870 AND 1100
                THEN (1013.25 * (baromin / 29.92))::numeric
            END AS pressure,
            -- Wind speed in m/s if in range, else NULL
            CASE
                WHEN (windspeedmph * 1.60934) BETWEEN 0 AND 120
                THEN (windspeedmph * 1.60934)::numeric
            END AS windspeed,
            -- Wind gust speed in m/s if in range, else NULL
            CASE
                WHEN (windgustmph * 1.60934) BETWEEN 0 AND 120
                THEN (windgustmph * 1.60934)::numeric
            END AS windgustspeed,
            -- Wind direction if in range, else NULL
            CASE
                WHEN winddir BETWEEN 0 AND 360
                THEN winddir::numeric
            END AS winddir,
            -- Wind gust direction if in range, else NULL
            CASE
                WHEN windgustdir BETWEEN 0 AND 360
                THEN windgustdir::numeric
            END AS windgustdir,
            -- Visibility if in range, else NULL
            CASE
                WHEN visibility >= 0
                THEN visibility::numeric
            END AS visibility,
            -- Soil moisture if in range, else NULL
            CASE
                WHEN soilmoisture BETWEEN 0 AND 100
                THEN soilmoisture::numeric
            END AS soilmoisture,
            -- Soil temperature in Celsius if in range, else NULL
            CASE 
                WHEN ((soiltempf - 32) / 1.8) BETWEEN -90 AND 60
                THEN ((soiltempf - 32) / 1.8)::numeric
            END AS soiltemperature,
            -- Daily Rainin in mm if in range, else NULL
            CASE
                WHEN (dailyrainin * 25.4) BETWEEN 0 AND 300
                THEN (dailyrainin * 25.4)::numeric
            END AS dailyrainin,
            -- Rainin in mm/h
            (rainin * 25.4)::numeric AS rainin,
            -- Rain duration in seconds
            CASE 
                WHEN dailyrainin > (LAG(dailyrainin) OVER (PARTITION BY site_id ORDER BY (o.dateutc AT TIME ZONE 'UTC' AT TIME ZONE s.timezone))) 
                THEN EXTRACT(EPOCH FROM ((o.dateutc AT TIME ZONE 'UTC' AT TIME ZONE s.timezone) - (LAG((o.dateutc AT TIME ZONE 'UTC' AT TIME ZONE s.timezone)) OVER (PARTITION BY site_id ORDER BY (o.dateutc AT TIME ZONE 'UTC' AT TIME ZONE s.timezone))))) 
                ELSE 0 
            END AS rainduration
        FROM observations o
        JOIN sites s ON s.id = o.site_id
        WHERE o.deleted_at IS NULL AND s.deleted_at IS NULL
    )
    SELECT
        site_id,
        DATE(datelocal) AS date,
        ROUND(MIN(temperature), 2) AS min_temperature,
        ROUND(MAX(temperature), 2) AS max_temperature,
        ROUND(AVG(temperature), 2) AS avg_temperature,
        ROUND(AVG(dewpoint), 2) AS avg_dewpoint,
        ROUND(AVG(humidity), 2) AS avg_humidity,
        ROUND(AVG(pressure), 2) AS avg_pressure,
        ROUND(MAX(windspeed), 2) AS max_windspeed,
        ROUND(MAX(windgustspeed), 2) AS max_windgustspeed,
        ROUND(MAX(dailyrainin), 2) AS max_dailyrainin,
        ROUND(MAX(rainin), 2) AS max_rainin,
        ROUND(SUM(rainduration)) AS sum_rainduration,
        COUNT(*) AS count
    FROM cleaned
    GROUP BY 1, 2;