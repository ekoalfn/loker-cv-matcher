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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->nullable()->constrained('job_sources')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('company');
            $table->string('location')->nullable();
            $table->string('employment_type')->nullable();
            $table->integer('salary_min')->nullable();
            $table->integer('salary_max')->nullable();
            $table->string('salary_currency')->default('IDR');
            $table->text('description_raw')->nullable();
            $table->text('summary_ai')->nullable();
            $table->jsonb('tags')->nullable();
            $table->string('source_url');
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('scraped_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index on location for location-based filtering
            $table->index('location');

            // Index on employment_type for type filtering
            $table->index('employment_type');

            // Composite index for active + expiry queries
            $table->index(['is_active', 'expires_at']);
        });

        // Unique partial index on source_url excluding soft-deleted rows
        DB::statement('
            CREATE UNIQUE INDEX idx_jobs_source_url_unique
            ON jobs (source_url)
            WHERE deleted_at IS NULL
        ');

        // GIN index for full-text search in Indonesian
        DB::statement("
            CREATE INDEX idx_jobs_search
            ON jobs USING GIN (
                to_tsvector('indonesian', coalesce(title,'') || ' ' || coalesce(company,'') || ' ' || coalesce(location,''))
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
