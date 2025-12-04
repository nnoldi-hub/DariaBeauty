<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adaugă coloane pentru locația serviciilor în users (specialiști)
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('offers_at_salon')->default(true)->after('is_active');
            $table->boolean('offers_at_home')->default(false)->after('offers_at_salon');
            $table->string('salon_address')->nullable()->after('offers_at_home');
            $table->decimal('salon_lat', 10, 8)->nullable()->after('salon_address');
            $table->decimal('salon_lng', 11, 8)->nullable()->after('salon_lat');
        });

        // Adaugă coloane pentru locația serviciilor în services
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('available_at_salon')->default(true)->after('category');
            $table->boolean('available_at_home')->default(false)->after('available_at_salon');
            $table->decimal('home_service_fee', 8, 2)->default(0)->after('available_at_home')->comment('Taxa suplimentara pentru serviciu la domiciliu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['offers_at_salon', 'offers_at_home', 'salon_address', 'salon_lat', 'salon_lng']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['available_at_salon', 'available_at_home', 'home_service_fee']);
        });
    }
};
