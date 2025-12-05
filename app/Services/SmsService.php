<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SmsService
{
    protected $client;
    protected $from;
    protected $enabled;

    public function __construct()
    {
        $this->enabled = config('twilio.enabled', false);
        
        if ($this->enabled) {
            $this->client = new Client(
                config('twilio.sid'),
                config('twilio.auth_token')
            );
            $this->from = config('twilio.phone_number');
        }
    }

    /**
     * Send SMS message
     */
    public function send(string $to, string $message): bool
    {
        if (!$this->enabled) {
            Log::info('SMS disabled. Message:', ['to' => $to, 'message' => $message]);
            return false;
        }

        try {
            // Normalize phone number
            $to = $this->normalizePhoneNumber($to);

            // Check rate limit
            if (!$this->checkRateLimit($to)) {
                Log::warning('SMS rate limit exceeded', ['to' => $to]);
                return false;
            }

            // Send SMS
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message
            ]);

            // Log success
            Log::info('SMS sent successfully', ['to' => $to]);
            
            // Increment rate limit counter
            $this->incrementRateLimit($to);

            return true;

        } catch (\Exception $e) {
            Log::error('SMS send failed', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send appointment confirmation SMS
     */
    public function sendAppointmentConfirmation($appointment): bool
    {
        $message = $this->buildMessage('appointment_confirmed', [
            'name' => $appointment->client_name,
            'service' => $appointment->service->name ?? 'serviciul selectat',
            'date' => $appointment->appointment_date->format('d.m.Y'),
            'time' => $appointment->appointment_date->format('H:i'),
        ]);

        return $this->send($appointment->client_phone, $message);
    }

    /**
     * Send appointment reminder (24h before)
     */
    public function sendAppointmentReminder($appointment): bool
    {
        $message = $this->buildMessage('appointment_reminder', [
            'service' => $appointment->service->name ?? 'serviciul selectat',
            'time' => $appointment->appointment_date->format('H:i'),
        ]);

        return $this->send($appointment->client_phone, $message);
    }

    /**
     * Send appointment cancellation SMS
     */
    public function sendAppointmentCancellation($appointment): bool
    {
        $message = $this->buildMessage('appointment_cancelled', [
            'date' => $appointment->appointment_date->format('d.m.Y'),
            'time' => $appointment->appointment_date->format('H:i'),
        ]);

        return $this->send($appointment->client_phone, $message);
    }

    /**
     * Notify specialist about new appointment
     */
    public function notifySpecialistNewAppointment($appointment, $specialist): bool
    {
        if (!$specialist->phone) {
            return false;
        }

        $message = $this->buildMessage('new_appointment_specialist', [
            'client' => $appointment->client_name,
            'service' => $appointment->service->name ?? 'serviciu',
            'date' => $appointment->appointment_date->format('d.m.Y'),
            'time' => $appointment->appointment_date->format('H:i'),
        ]);

        return $this->send($specialist->phone, $message);
    }

    /**
     * Send verification code
     */
    public function sendVerificationCode(string $phone, string $code): bool
    {
        // Check verification rate limit
        $key = 'sms_verification_' . $phone;
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= config('twilio.rate_limit.verification', 3)) {
            Log::warning('Verification SMS rate limit exceeded', ['phone' => $phone]);
            return false;
        }

        $message = $this->buildMessage('verification_code', [
            'code' => $code
        ]);

        $sent = $this->send($phone, $message);

        if ($sent) {
            Cache::put($key, $attempts + 1, now()->addHour());
        }

        return $sent;
    }

    /**
     * Send password reset code
     */
    public function sendPasswordResetCode(string $phone, string $code): bool
    {
        $message = $this->buildMessage('password_reset', [
            'code' => $code
        ]);

        return $this->send($phone, $message);
    }

    /**
     * Build message from template
     */
    protected function buildMessage(string $template, array $data): string
    {
        $message = config("twilio.templates.{$template}");

        foreach ($data as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }

        return $message;
    }

    /**
     * Normalize phone number to E.164 format
     */
    protected function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Add country code if not present
        if (!str_starts_with($phone, '40') && !str_starts_with($phone, '+40')) {
            // Remove leading zero if present
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            $phone = '40' . $phone;
        }

        // Add + prefix
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Check rate limit for phone number
     */
    protected function checkRateLimit(string $phone): bool
    {
        $key = 'sms_limit_' . $phone;
        $count = Cache::get($key, 0);
        
        return $count < config('twilio.rate_limit.per_user', 10);
    }

    /**
     * Increment rate limit counter
     */
    protected function incrementRateLimit(string $phone): void
    {
        $key = 'sms_limit_' . $phone;
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, now()->endOfDay());
    }

    /**
     * Check if SMS service is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
