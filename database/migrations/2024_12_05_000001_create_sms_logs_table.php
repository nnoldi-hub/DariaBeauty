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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->text('message');
            $table->enum('type', [
                'appointment_confirmation',
                'appointment_reminder',
                'appointment_cancelled',
                'verification_code',
                'password_reset',
                'notification'
            ]);
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
            $table->text('error_message')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index(['to', 'created_at']);
            $table->index('status');
            $table->index('type');
        });

        // Add phone_verified_at to users table
        if (!Schema::hasColumn('users', 'phone_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            });
        }

        // Add SMS preferences to users
        if (!Schema::hasColumn('users', 'sms_notifications')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('sms_notifications')->default(true)->after('email');
                $table->boolean('sms_reminders')->default(true)->after('sms_notifications');
                $table->boolean('sms_marketing')->default(false)->after('sms_reminders');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
        
        if (Schema::hasColumn('users', 'phone_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('phone_verified_at');
            });
        }

        if (Schema::hasColumn('users', 'sms_notifications')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['sms_notifications', 'sms_reminders', 'sms_marketing']);
            });
        }
    }
};
