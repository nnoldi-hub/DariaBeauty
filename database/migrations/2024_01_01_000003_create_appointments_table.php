<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            
            // Date client
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone');
            
            // Programare
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            
            // Servicii la domiciliu - caracteristica principala DariaBeauty
            $table->boolean('is_home_service')->default(true);
            $table->string('client_address')->nullable();
            $table->string('client_city')->nullable();
            $table->string('client_zone')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('transport_fee', 8, 2)->default(0);
            $table->integer('estimated_travel_time')->nullable(); // minute
            $table->text('special_instructions')->nullable();
            
            // Plati
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->decimal('total_amount', 8, 2)->nullable();
            
            // Review
            $table->string('review_token')->nullable()->unique();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};