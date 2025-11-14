<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('duration'); // in minute
            $table->enum('sub_brand', ['dariaNails', 'dariaHair', 'dariaGlow']);
            $table->boolean('is_mobile')->default(true); // se poate face la domiciliu
            $table->json('equipment_needed')->nullable(); // echipamente necesare
            $table->integer('preparation_time')->default(0); // minute pregatire
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};