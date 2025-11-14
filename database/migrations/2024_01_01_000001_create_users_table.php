<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['specialist', 'superadmin'])->default('specialist');
            $table->string('phone')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('description')->nullable();
            
            // Campuri pentru sub-branduri DariaBeauty
            $table->enum('sub_brand', ['dariaNails', 'dariaHair', 'dariaGlow'])->nullable();
            
            // Campuri pentru servicii mobile
            $table->json('coverage_area')->nullable(); // zone de acoperire
            $table->json('mobile_equipment')->nullable(); // echipamente mobile
            $table->decimal('transport_fee', 8, 2)->default(0); // tarif per km
            $table->integer('max_distance')->default(50); // km maxim
            
            $table->boolean('is_active')->default(true);
            $table->string('address')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};