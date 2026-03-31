<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE activities MODIFY activity_type_id BIGINT UNSIGNED NOT NULL DEFAULT 1');
        DB::statement('ALTER TABLE activities MODIFY request_source_id BIGINT UNSIGNED NOT NULL DEFAULT 1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE activities MODIFY activity_type_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE activities MODIFY request_source_id BIGINT UNSIGNED NOT NULL');
    }
};
