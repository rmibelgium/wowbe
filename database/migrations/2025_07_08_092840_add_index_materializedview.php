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
        DB::statement('CREATE UNIQUE INDEX observations_5min_agg_unique_idx ON observations_5min_agg(datetime, site_id);');
        DB::statement('CREATE UNIQUE INDEX observations_day_agg_unique_idx ON observations_day_agg(date, site_id);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS observations_5min_agg_unique_idx;');
        DB::statement('DROP INDEX IF EXISTS observations_day_agg_unique_idx;');
    }
};
