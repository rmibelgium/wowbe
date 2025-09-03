<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('observations_agg_5min', function (Blueprint $table) {
            $table->renameColumn('rainin', 'rain');
            $table->renameColumn('dailyrainin', 'dailyrain');
        });

        Schema::table('observations_agg_5min_local', function (Blueprint $table) {
            $table->renameColumn('rainin', 'rain');
            $table->renameColumn('dailyrainin', 'dailyrain');
        });

        Schema::table('observations_agg_day', function (Blueprint $table) {
            $table->renameColumn('max_rainin', 'max_rain');
            $table->renameColumn('max_dailyrainin', 'max_dailyrain');
        });

        Schema::table('observations_agg_day_local', function (Blueprint $table) {
            $table->renameColumn('max_rainin', 'max_rain');
            $table->renameColumn('max_dailyrainin', 'max_dailyrain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('observations_agg_5min', function (Blueprint $table) {
            $table->renameColumn('rain', 'rainin');
            $table->renameColumn('dailyrain', 'dailyrainin');
        });

        Schema::table('observations_agg_5min_local', function (Blueprint $table) {
            $table->renameColumn('rain', 'rainin');
            $table->renameColumn('dailyrain', 'dailyrainin');
        });

        Schema::table('observations_agg_day', function (Blueprint $table) {
            $table->renameColumn('max_rain', 'max_rainin');
            $table->renameColumn('max_dailyrain', 'max_dailyrainin');
        });

        Schema::table('observations_agg_day_local', function (Blueprint $table) {
            $table->renameColumn('max_rain', 'max_rainin');
            $table->renameColumn('max_dailyrain', 'max_dailyrainin');
        });
    }
};
