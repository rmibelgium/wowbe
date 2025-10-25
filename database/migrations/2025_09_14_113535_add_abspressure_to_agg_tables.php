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
            $table->float('abspressure')->nullable();
        });
        Schema::table('observations_agg_5min_local', function (Blueprint $table) {
            $table->float('abspressure')->nullable();
        });
        Schema::table('observations_agg_day', function (Blueprint $table) {
            $table->float('avg_abspressure')->nullable();
        });
        Schema::table('observations_agg_day_local', function (Blueprint $table) {
            $table->float('avg_abspressure')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('observations_agg_5min', function (Blueprint $table) {
            $table->dropColumn('abspressure');
        });
        Schema::table('observations_agg_5min_local', function (Blueprint $table) {
            $table->dropColumn('abspressure');
        });
        Schema::table('observations_agg_day', function (Blueprint $table) {
            $table->dropColumn('avg_abspressure');
        });
        Schema::table('observations_agg_day_local', function (Blueprint $table) {
            $table->dropColumn('avg_abspressure');
        });
    }
};
