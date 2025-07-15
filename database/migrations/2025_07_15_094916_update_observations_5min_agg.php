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
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS observations_5min_agg;');
        DB::statement(file_get_contents(__DIR__.'/sql/observations_5min_agg.sql'));
        DB::statement('CREATE UNIQUE INDEX observations_5min_agg_unique_idx ON observations_5min_agg(datetime, site_id);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
