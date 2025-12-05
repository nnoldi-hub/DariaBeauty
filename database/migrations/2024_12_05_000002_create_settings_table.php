<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'platform_name', 'value' => 'DariaBeauty', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_email', 'value' => 'contact@dariabeauty.ro', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_phone', 'value' => '+40 XXX XXX XXX', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'platform_commission', 'value' => '15', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_start_time', 'value' => '09:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_end_time', 'value' => '18:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'notify_new_specialist', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'notify_new_booking', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'notify_negative_review', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
