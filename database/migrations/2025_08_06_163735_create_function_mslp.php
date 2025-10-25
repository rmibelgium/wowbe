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
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION mslp (baromin double precision, tempf double precision, altitude double precision)
                RETURNS numeric
            AS $$
                DECLARE
                    tempk numeric;
                    absbaromin numeric;
                BEGIN
                    IF baromin IS NULL OR tempf IS NULL OR altitude IS NULL THEN
                        RETURN NULL;
                    END IF;
                    tempk := (tempf - 32) * 5 / 9 + 273.15;
                    absbaromin := baromin * POWER((1 - (0.0065 * altitude) / (tempk + 0.0065 * altitude)), -5.257);
                    RETURN ROUND(absbaromin, 2);
                END;
            $$
            LANGUAGE plpgsql;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS mslp (baromin double precision, tempf double precision, altitude double precision);');
    }
};
