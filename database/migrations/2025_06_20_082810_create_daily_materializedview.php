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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS daily');
    }
};
