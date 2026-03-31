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
        Schema::table('volunteers', function (Blueprint $table) {
            $table->string('fullname')->after('id');
            $table->string('luogo_di_nascita')->nullable()->after('fullname');
            $table->string('numero_iscrizione_regionale')->nullable()->after('luogo_di_nascita');
            $table->string('residenza')->nullable()->after('numero_iscrizione_regionale');
            $table->string('cellulare')->nullable()->after('residenza');
            $table->string('email')->nullable()->after('cellulare');
            $table->text('patenti')->nullable()->after('email');
        });

        // Copia i dati esistenti in fullname
        DB::statement("UPDATE volunteers SET fullname = CONCAT(first_name, ' ', last_name) WHERE fullname IS NULL");

        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
        });

        // Split fullname back to first_name and last_name (semplificato, assume formato "nome cognome")
        DB::statement("UPDATE volunteers SET first_name = SUBSTRING_INDEX(fullname, ' ', 1), last_name = SUBSTRING_INDEX(fullname, ' ', -1) WHERE first_name IS NULL AND last_name IS NULL");

        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropColumn(['fullname', 'luogo_di_nascita', 'numero_iscrizione_regionale', 'residenza', 'cellulare', 'email', 'patenti']);
        });
    }
};
