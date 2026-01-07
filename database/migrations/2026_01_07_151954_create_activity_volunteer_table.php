<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_volunteer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('volunteer_id')->constrained()->cascadeOnDelete();

            // opzionali (se ti servono)
            $table->string('role')->nullable();
            $table->decimal('hours_on_activity', 6, 2)->nullable();

            $table->timestamps();

            $table->unique(['activity_id', 'volunteer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_volunteer');
    }
};
