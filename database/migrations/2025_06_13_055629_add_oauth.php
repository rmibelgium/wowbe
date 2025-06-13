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
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();

            $table->string('oauth_provider')->nullable();
            $table->string('oauth_id')->nullable();

            $table->unique(['oauth_provider', 'oauth_id'], 'users_oauth_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_oauth_unique');

            $table->dropColumn('oauth_provider');
            $table->dropColumn('oauth_id');

            $table->string('password')->nullable(false)->change();
        });
    }
};
