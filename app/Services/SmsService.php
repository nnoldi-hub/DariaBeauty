<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\SmsLog;

class SmsService
{
    protected $client;
    protected $from;
    protected $enabled;
    protected $whatsappEnabled;
    protected $whatsappFrom;

    public function __construct()
    {
        $this->enabled = config('twilio.enabled', false);
        $this->whatsappEnabled = config('twilio.whatsapp_enabled', false);
        
        if ($this->enabled || $this->whatsappEnabled) {
            $this->client = new Client(
                config('twilio.sid'),
                config('twilio.auth_token')
            );
            $this->from = config('twilio.phone_number');
            $this->whatsappFrom = config('twilio.whatsapp_number');
        }
    }

    /**
     * Send SMS message
     */
    public function send(string $to, string $message, string $type = 'general', $appointmentId = null, $userId = null): bool
    {
        Log::info(">>> SMS SEND METHOD CALLED <<<", [
            'to' => $to,
            'message_length' => strlen($message),
            'type' => $type,
            'enabled' => $this->enabled
        ]);

        if (!$this->enabled) {
            Log::warning('SMS disabled in config!', ['to' => $to, 'message' => $message]);
            $this->logSms($to, $message, $type, 'failed', 'SMS service disabled', $appointmentId, $userId);
            return false;
        }

        try {
            // Normalize phone number
            $to = $this->normalizePhoneNumber($to);
            Log::info("Phone normalized to: {$to}");

            // Check rate limit
            if (!$this->checkRateLimit($to)) {
                Log::warning('SMS rate limit exceeded', ['to' => $to]);
                $this->logSms($to, $message, $type, 'failed', 'Rate limit exceeded', $appointmentId, $userId);
                return false;
            }

            // Send SMS
            Log::info('🚀 CALLING TWILIO API', [
                'from' => $this->from,
                'to' => $to,
                'message' => $message,
                'message_length' => strlen($message)
            ]);

            $twilioResponse = $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message
            ]);

            // Log success with Twilio response
            Log::info('✅ SMS sent successfully - Twilio Response', [
                'to' => $to,
                'sid' => $twilioResponse->sid,
                'status' => $twilioResponse->status,
                'direction' => $twilioResponse->direction,
                'price' => $twilioResponse->price,
                'error_code' => $twilioResponse->errorCode,
                'error_message' => $twilioResponse->errorMessage
            ]);
            
            // Increment rate limit counter
            $this->incrementRateLimit($to);

            // Log to database
            $this->logSms($to, $message, $type, 'sent', null, $appointmentId, $userId);

            return true;

        } catch (\Exception $e) {
            Log::error('SMS send failed', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            $this->logSms($to, $message, $type, 'failed', $e->getMessage(), $appointmentId, $userId);
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
            'time' => $appointment->appointment_time, // Folosim appointment_time, nu appointment_date
        ]);

        return $this->send(
            $appointment->client_phone, 
            $message, 
            'appointment_confirmed',
            $appointment->id,
            $appointment->user_id
        );
    }

    /**
     * Send appointment reminder (24h before)
     */
    public function sendAppointmentReminder($appointment): bool
    {
        $message = $this->buildMessage('appointment_reminder', [
            'service' => $appointment->service->name ?? 'serviciul selectat',
            'time' => $appointment->appointment_time, // Folosim appointment_time
        ]);

        return $this->send(
            $appointment->client_phone, 
            $message,
            'appointment_reminder',
            $appointment->id,
            $appointment->user_id
        );
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

        return $this->send(
            $appointment->client_phone, 
            $message,
            'appointment_cancelled',
            $appointment->id,
            $appointment->user_id
        );
    }

    /**
     * Notify specialist about new appointment
     */
    public function notifySpecialistNewAppointment($appointment, $specialist): bool
    {
        if (!$specialist->phone) {
            Log::warning('Specialist has no phone number', [
                'specialist_id' => $specialist->id,
                'appointment_id' => $appointment->id
            ]);
            return false;
        }

        Log::info('Building SMS for specialist', [
            'specialist_phone' => $specialist->phone,
            'client' => $appointment->client_name,
            'service' => $appointment->service->name ?? 'serviciu'
        ]);

        $message = $this->buildMessage('new_appointment_specialist', [
            'client' => $appointment->client_name,
            'phone' => $appointment->client_phone,
            'service' => $appointment->service->name ?? 'serviciu',
            'date' => $appointment->appointment_date->format('d.m.Y'),
            'time' => $appointment->appointment_time,
        ]);

        Log::info('Sending SMS to specialist', [
            'to' => $specialist->phone,
            'message_preview' => substr($message, 0, 50)
        ]);

        return $this->send(
            $specialist->phone, 
            $message,
            'new_appointment_specialist',
            $appointment->id,
            $specialist->id
        );
    }

    /**
     * Notify specialist about appointment cancellation by client
     */
    public function notifySpecialistCancellation($appointment, $specialist): bool
    {
        if (!$specialist->phone) {
            return false;
        }

        $message = "DariaBeauty - Programare anulata!\nClient: {$appointment->client_name}\nServiciu: {$appointment->service->name}\nData: {$appointment->appointment_date->format('d.m.Y')} la {$appointment->appointment_time}";

        return $this->send(
            $specialist->phone, 
            $message,
            'appointment_cancelled_specialist',
            $appointment->id,
            $specialist->id
        );
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
        $limit = config('twilio.rate_limit.per_user', 10);
        
        Log::info('📊 Rate limit check', [
            'phone' => $phone,
            'current_count' => $count,
            'limit' => $limit,
            'allowed' => $count < $limit
        ]);
        
        return $count < $limit;
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
     * Send appointment completion notification with review request
     */
    public function sendAppointmentCompletedWithReview($appointment): bool
    {
        // Generate review token if not exists
        if (!$appointment->review_token) {
            $appointment->generateReviewToken();
        }

        // Build review link
        $reviewLink = url("/review/{$appointment->review_token}");

        // Încearcă WhatsApp mai întâi (dacă e activat), apoi SMS
        if ($this->whatsappEnabled) {
            $message = "Bună {$appointment->client_name}! 🎉\n\n";
            $message .= "Mulțumim că ai ales DariaBeauty!\n\n";
            $message .= "Ne-ar face plăcere să ne lași un review:\n";
            $message .= $reviewLink . "\n\n";
            $message .= "Echipa DariaBeauty ❤️";
            
            $result = $this->sendWhatsApp(
                $appointment->client_phone,
                $message,
                'appointment_completed',
                $appointment->id,
                $appointment->user_id
            );
            
            if ($result) {
                return true;
            }
            
            // Dacă WhatsApp eșuează, încearcă SMS fără link
            Log::info("WhatsApp failed, falling back to SMS");
        }
        
        // SMS fără link (pentru a evita blocarea)
        $message = $this->buildMessage('appointment_completed', [
            'name' => $appointment->client_name,
            'review_link' => '', // Nu trimitem link prin SMS
        ]);

        return $this->send(
            $appointment->client_phone, 
            $message,
            'appointment_completed',
            $appointment->id,
            $appointment->user_id
        );
    }

    /**
     * Notify specialist about received review
     */
    public function notifySpecialistReview($review, $specialist): bool
    {
        if (!$specialist->phone) {
            Log::warning('Specialist has no phone number', [
                'specialist_id' => $specialist->id,
                'review_id' => $review->id
            ]);
            return false;
        }

        $reviewLink = url("/specialist/reviews");

        $message = $this->buildMessage('specialist_review_received', [
            'client' => $review->client_name ?? 'Un client',
            'rating' => $review->rating,
            'review_link' => $reviewLink,
        ]);

        return $this->send(
            $specialist->phone, 
            $message,
            'specialist_review_received',
            null,
            $specialist->id
        );
    }

    /**
     * Send WhatsApp message via Twilio
     */
    public function sendWhatsApp(string $to, string $message, string $type = 'general', $appointmentId = null, $userId = null): bool
    {
        Log::info(">>> WHATSAPP SEND METHOD CALLED <<<", [
            'to' => $to,
            'message_length' => strlen($message),
            'type' => $type,
            'enabled' => $this->whatsappEnabled
        ]);

        if (!$this->whatsappEnabled) {
            Log::warning('WhatsApp disabled in config!', ['to' => $to]);
            return false;
        }

        try {
            // Normalize phone number for WhatsApp (format: whatsapp:+40...)
            $to = $this->normalizePhoneNumber($to);
            $whatsappTo = 'whatsapp:' . $to;
            
            Log::info('🚀 CALLING TWILIO WHATSAPP API', [
                'from' => $this->whatsappFrom,
                'to' => $whatsappTo,
                'message_length' => strlen($message)
            ]);

            $twilioResponse = $this->client->messages->create($whatsappTo, [
                'from' => $this->whatsappFrom,
                'body' => $message
            ]);

            Log::info('✅ WhatsApp sent successfully', [
                'to' => $whatsappTo,
                'sid' => $twilioResponse->sid,
                'status' => $twilioResponse->status
            ]);
            
            // Log to database
            $this->logSms($to, $message, $type . '_whatsapp', 'sent', null, $appointmentId, $userId);

            return true;

        } catch (\Exception $e) {
            Log::error('WhatsApp send failed', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            $this->logSms($to, $message, $type . '_whatsapp', 'failed', $e->getMessage(), $appointmentId, $userId);
            return false;
        }
    }

    /**
     * Log SMS to database
     */
    protected function logSms(string $to, string $message, string $type, string $status, ?string $errorMessage = null, $appointmentId = null, $userId = null): void
    {
        try {
            SmsLog::create([
                'to' => $to,
                'message' => $message,
                'type' => $type,
                'status' => $status,
                'error_message' => $errorMessage,
                'appointment_id' => $appointmentId,
                'user_id' => $userId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log SMS to database', [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if SMS service is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
