<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {

            
            if (!Schema::hasColumn('activities', 'activity_type_id')) {
                $table->foreignId('activity_type_id')
                    ->after('id')
                    ->constrained('activity_types')
                    ->restrictOnDelete();
            }

            if (!Schema::hasColumn('activities', 'request_source_id')) {
                $table->foreignId('request_source_id')
                 ->nullable() // ✅ IMPORTANT
                    ->after('activity_type_id')
                    ->constrained('request_sources')
                    ->restrictOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {

            if (Schema::hasColumn('activities', 'request_source_id')) {
                $table->dropForeign(['request_source_id']);
                $table->dropColumn('request_source_id');
            }

            if (Schema::hasColumn('activities', 'activity_type_id')) {
                $table->dropForeign(['activity_type_id']);
                $table->dropColumn('activity_type_id');
            }
        });
    }
};
