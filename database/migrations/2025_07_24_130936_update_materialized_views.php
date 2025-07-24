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
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_day_agg;');
        DB::statement(file_get_contents(__DIR__.'/sql/observations_day_agg.sql'));
        DB::statement('CREATE UNIQUE INDEX observations_day_agg_unique_idx ON observations_day_agg(date, site_id);');

        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_day_agg_local;');
        DB::statement(file_get_contents(__DIR__.'/sql/observations_day_agg_local.sql'));
        DB::statement('CREATE UNIQUE INDEX observations_day_agg_local_unique_idx ON observations_day_agg_local(date, site_id);');

        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_5min_agg;');
        DB::statement(file_get_contents(__DIR__.'/sql/observations_5min_agg.sql'));
        DB::statement('CREATE UNIQUE INDEX observations_5min_agg_unique_idx ON observations_5min_agg(dateutc, site_id);');

        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_5min_agg_local;');
        DB::statement(file_get_contents(__DIR__.'/sql/observations_5min_agg_local.sql'));
        DB::statement('CREATE UNIQUE INDEX observations_5min_agg_local_unique_idx ON observations_5min_agg_local(datelocal, site_id);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
