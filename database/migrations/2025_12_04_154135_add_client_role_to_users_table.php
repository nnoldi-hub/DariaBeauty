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
        // Modifică enum-ul pentru a include 'client'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('client', 'specialist', 'superadmin') DEFAULT 'client'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenire la enum-ul original
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('specialist', 'superadmin') DEFAULT 'specialist'");
    }
};
