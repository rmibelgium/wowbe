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
        Schema::table('sites', function (Blueprint $table) {
            // Add the short_id column to the sites table
            $table->string('short_id')->nullable()->unique()->after('id');
        });

        // Populate the short_id for existing sites
        DB::table('sites')->get()->each(function ($site) {
            DB::table('sites')
                ->where('id', $site->id)
                ->update(['short_id' => hash('xxh32', $site->id)]);
        });

        Schema::table('sites', function (Blueprint $table) {
            // Set the short_id column to be not nullable
            $table->string('short_id')->nullable(false)->change();

            // Create an index for the short_id column
            $table->index('short_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            // Drop the index for the short_id column
            $table->dropIndex(['short_id']);

            // Drop the short_id column
            $table->dropColumn('short_id');
        });
    }
};
