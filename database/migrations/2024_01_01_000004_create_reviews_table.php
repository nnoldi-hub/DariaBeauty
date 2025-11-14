<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('specialist_id')->constrained('users')->onDelete('cascade');
            $table->string('client_name');
            $table->integer('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_featured')->default(false); // pentru homepage
            $table->json('photos')->nullable(); // poze cu rezultatele
            
            // Evaluari detaliate pentru servicii DariaBeauty
            $table->integer('service_quality_rating')->nullable(); // 1-5
            $table->integer('punctuality_rating')->nullable(); // 1-5  
            $table->integer('cleanliness_rating')->nullable(); // 1-5
            $table->integer('overall_experience')->nullable(); // 1-5
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};