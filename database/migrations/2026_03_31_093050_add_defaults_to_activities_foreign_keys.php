<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('UPDATE activities SET activity_type_id = 1 WHERE activity_type_id IS NULL');
            DB::statement('UPDATE activities SET request_source_id = 1 WHERE request_source_id IS NULL');

            DB::statement('ALTER TABLE activities ALTER COLUMN activity_type_id SET DEFAULT 1');
            DB::statement('ALTER TABLE activities ALTER COLUMN request_source_id SET DEFAULT 1');

            DB::statement('ALTER TABLE activities ALTER COLUMN activity_type_id SET NOT NULL');
            DB::statement('ALTER TABLE activities ALTER COLUMN request_source_id SET NOT NULL');
        } else {
            // MySQL
            DB::statement('ALTER TABLE activities MODIFY activity_type_id BIGINT UNSIGNED NOT NULL DEFAULT 1');
            DB::statement('ALTER TABLE activities MODIFY request_source_id BIGINT UNSIGNED NOT NULL DEFAULT 1');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE activities ALTER COLUMN activity_type_id DROP DEFAULT');
            DB::statement('ALTER TABLE activities ALTER COLUMN request_source_id DROP DEFAULT');

            DB::statement('ALTER TABLE activities ALTER COLUMN activity_type_id DROP NOT NULL');
            DB::statement('ALTER TABLE activities ALTER COLUMN request_source_id DROP NOT NULL');
        } else {
            DB::statement('ALTER TABLE activities MODIFY activity_type_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE activities MODIFY request_source_id BIGINT UNSIGNED NOT NULL');
        }
    }
};