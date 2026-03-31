<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->string('category'); // categoria attività
            $table->string('requested_by')->nullable(); // richiesto da
            $table->string('short_description', 255); // breve descrizione

            $table->dateTime('date_from');
            $table->dateTime('date_to');

            $table->decimal('hours', 6, 2)->nullable(); // opzionale v1

            $table->foreignId('activity_type_id')->constrained('activity_types');
            $table->foreignId('request_source_id')->constrained('request_sources');

            $table->timestamps();

            $table->index(['category']);
            $table->index(['date_from', 'date_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};