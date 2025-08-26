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
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_5min_agg');
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_5min_agg_local');
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_day_agg');
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_day_agg_local');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
