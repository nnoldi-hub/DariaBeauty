<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adaugă coloana duration la appointments pentru a ști cât durează fiecare programare
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('duration')->default(60)->after('appointment_time'); // durata în minute
        });
        
        // Actualizează programările existente cu durata serviciului
        \DB::statement("
            UPDATE appointments a
            JOIN services s ON a.service_id = s.id
            SET a.duration = s.duration
            WHERE a.duration = 60 OR a.duration IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
