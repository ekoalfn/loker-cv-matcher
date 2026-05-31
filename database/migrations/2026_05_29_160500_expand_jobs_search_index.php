<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP INDEX IF EXISTS idx_jobs_search');
        DB::statement("
            CREATE INDEX idx_jobs_search
            ON jobs USING GIN (
                to_tsvector('indonesian', coalesce(title,'') || ' ' || coalesce(company,'') || ' ' || coalesce(location,'') || ' ' || coalesce(summary_ai,'') || ' ' || coalesce(description_raw,'') || ' ' || coalesce(tags::text,''))
            )
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP INDEX IF EXISTS idx_jobs_search');
        DB::statement("
            CREATE INDEX idx_jobs_search
            ON jobs USING GIN (
                to_tsvector('indonesian', coalesce(title,'') || ' ' || coalesce(company,'') || ' ' || coalesce(location,''))
            )
        ");
    }
};
