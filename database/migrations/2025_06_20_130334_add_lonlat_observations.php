<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('observations', function (Blueprint $table) {
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->double('altitude')->nullable();
        });

        DB::statement('UPDATE observations SET longitude = s.longitude, latitude = s.latitude, altitude = s.altitude FROM sites s WHERE observations.site_id = s.id');

        Schema::table('observations', function (Blueprint $table) {
            $table->double('longitude')->nullable(false)->change();
            $table->double('latitude')->nullable(false)->change();
            $table->double('altitude')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('observations', function (Blueprint $table) {
            $table->dropColumn(['longitude', 'latitude', 'altitude']);
        });
    }
};
