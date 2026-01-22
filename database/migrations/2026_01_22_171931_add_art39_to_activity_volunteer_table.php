<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('activity_volunteer', function (Blueprint $table) {
            $table->boolean('art39')->default(false)->after('hours_on_activity');
        });
    }

    public function down(): void
    {
        Schema::table('activity_volunteer', function (Blueprint $table) {
            $table->dropColumn('art39');
        });
    }
};
