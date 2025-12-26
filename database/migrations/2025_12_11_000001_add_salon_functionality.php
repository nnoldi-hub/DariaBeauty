<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adaugă funcționalitate de salon: rol 'salon' și relație salon_id
     */
    public function up(): void
    {
        // 1. Modifică enum-ul pentru a include 'salon'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('client', 'specialist', 'salon', 'superadmin') DEFAULT 'client'");
        
        // 2. Adaugă coloana salon_id pentru specialiști care lucrează într-un salon
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('salon_id')->nullable()->after('role');
            $table->foreign('salon_id')->references('id')->on('users')->onDelete('set null');
            
            // Index pentru performanță
            $table->index('salon_id');
        });
        
        // 3. Adaugă câmp pentru a indica dacă un salon e activ
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_salon_owner')->default(false)->after('salon_id');
            $table->text('salon_description')->nullable()->after('is_salon_owner');
            $table->string('salon_logo')->nullable()->after('salon_description');
            $table->integer('salon_specialists_count')->default(0)->after('salon_logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['salon_id']);
            $table->dropColumn(['salon_id', 'is_salon_owner', 'salon_description', 'salon_logo', 'salon_specialists_count']);
        });
        
        // Revenire la enum-ul anterior
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('client', 'specialist', 'superadmin') DEFAULT 'client'");
    }
};
