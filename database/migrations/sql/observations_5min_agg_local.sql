CREATE MATERIALIZED VIEW observations_5min_agg_local AS
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
                WHEN absbaromin IS NOT NULL AND (1013.25 * (absbaromin / 29.92)) BETWEEN 870 AND 1100
                THEN (1013.25 * (absbaromin / 29.92))::numeric
                WHEN absbaromin IS NULL AND (baromin IS NOT NULL AND tempf IS NOT NULL AND s.altitude IS NOT NULL) AND (1013.25 * (mslp(baromin, tempf, s.altitude) / 29.92)) BETWEEN 870 AND 1100
                THEN (1013.25 * (mslp(baromin, tempf, s.altitude) / 29.92))::numeric
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
            -- Solar radiation if in range, else NULL
            CASE 
                WHEN solarradiation BETWEEN 0 AND 1100
                THEN solarradiation::numeric
            END AS solarradiation
        FROM observations o
        JOIN sites s ON s.id = o.site_id
        WHERE o.deleted_at IS NULL AND s.deleted_at IS NULL
    )
    SELECT
        site_id,
        date_trunc('minute', datelocal) - INTERVAL '1 minute' * (extract(minute from datelocal)::int % 5) AS datelocal,
        ROUND(AVG(temperature), 2) AS temperature,
        ROUND(AVG(dewpoint), 2) AS dewpoint,
        ROUND(AVG(humidity), 2) AS humidity,
        ROUND(AVG(pressure), 2) AS pressure,
        ROUND(AVG(windspeed), 2) AS windspeed,
        ROUND(AVG(windgustspeed), 2) AS windgustspeed,
        ROUND(AVG(winddir), 2) AS winddir,
        ROUND(AVG(windgustdir), 2) AS windgustdir,
        ROUND(AVG(visibility), 2) AS visibility,
        ROUND(AVG(soilmoisture), 2) AS soilmoisture,
        ROUND(AVG(soiltemperature), 2) AS soiltemperature,
        ROUND(MAX(dailyrainin), 2) AS dailyrainin,
        ROUND(AVG(rainin), 2) AS rainin,
        ROUND(AVG(solarradiation), 2) AS solarradiation,
        COUNT(*) AS count
    FROM cleaned
    GROUP BY 1, 2;