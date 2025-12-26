<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creează tabelul pentru programul de lucru al specialiștilor
     */
    public function up(): void
    {
        Schema::create('specialist_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')->constrained('users')->onDelete('cascade');
            
            // Ziua săptămânii: 0=Duminică, 1=Luni, 2=Marți, etc.
            $table->tinyInteger('day_of_week'); // 0-6
            
            // Disponibilitate la salon
            $table->boolean('available_at_salon')->default(false);
            $table->time('salon_start_time')->nullable(); // ex: 09:00
            $table->time('salon_end_time')->nullable();   // ex: 18:00
            
            // Disponibilitate pentru deplasări/domiciliu
            $table->boolean('available_at_home')->default(false);
            $table->time('home_start_time')->nullable();  // ex: 10:00
            $table->time('home_end_time')->nullable();    // ex: 20:00
            
            // Pauză de masă (opțional)
            $table->time('break_start_time')->nullable(); // ex: 13:00
            $table->time('break_end_time')->nullable();   // ex: 14:00
            
            // Note pentru ziua respectivă
            $table->string('notes')->nullable();
            
            $table->timestamps();
            
            // Un specialist poate avea o singură înregistrare per zi
            $table->unique(['specialist_id', 'day_of_week']);
        });
        
        // Adaugă și câmpuri pentru setări generale în users
        Schema::table('users', function (Blueprint $table) {
            // Interval între programări (minute) - pentru a lăsa timp între clienți
            $table->integer('slot_interval')->default(30)->after('transport_fee');
            // Timp minim de notificare înainte de programare (ore)
            $table->integer('min_booking_notice')->default(2)->after('slot_interval');
            // Zile în avans pentru programări
            $table->integer('max_booking_days')->default(30)->after('min_booking_notice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialist_schedules');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['slot_interval', 'min_booking_notice', 'max_booking_days']);
        });
    }
};
