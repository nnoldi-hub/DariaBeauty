<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->enum('sub_brand', ['dariaNails', 'dariaHair', 'dariaGlow']);
            $table->enum('before_after', ['before', 'after', 'single'])->default('single');
            $table->boolean('is_featured')->default(false);
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->json('tags')->nullable(); // taguri pentru cautare
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gallery');
    }
};