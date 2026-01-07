<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate')->unique(); // targa
            $table->string('brand'); // marca (puoi aggiungere model in futuro)
            $table->string('model'); // marca (puoi aggiungere model in futuro)
            $table->date('revision_expires_at')->nullable();
            $table->date('insurance_expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }

};
?>
