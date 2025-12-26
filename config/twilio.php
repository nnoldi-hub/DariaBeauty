<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Twilio SMS Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Twilio SMS integration
    |
    */

    'enabled' => env('TWILIO_ENABLED', false),
    
    'sid' => env('TWILIO_SID'),
    
    'auth_token' => env('TWILIO_AUTH_TOKEN'),
    
    'phone_number' => env('TWILIO_PHONE_NUMBER'),
    
    // WhatsApp Configuration
    'whatsapp_enabled' => env('TWILIO_WHATSAPP_ENABLED', false),
    'whatsapp_number' => env('TWILIO_WHATSAPP_NUMBER'), // Format: whatsapp:+14155238886
    
    /*
    |--------------------------------------------------------------------------
    | SMS Templates
    |--------------------------------------------------------------------------
    |
    | Default message templates for various notifications
    |
    */
    
    'templates' => [
        'appointment_confirmed' => 'Buna {name}! Programarea ta la {service} pe {date} la {time} a fost confirmata. DariaBeauty',
        'appointment_reminder' => 'Reminder: Ai programare la {service} maine la {time}. Te asteptam! DariaBeauty',
        'appointment_cancelled' => 'Programarea ta din {date} la {time} a fost anulata. DariaBeauty',
        'appointment_completed' => 'Multumim {name}! Cum a fost experienta? Lasa-ne un review pe site-ul DariaBeauty. Vei primi un email cu detalii.',
        'new_appointment_specialist' => 'DariaBeauty - Programare noua! Client: {client} ({phone}), Serviciu: {service}, Data: {date} la {time}.',
        'specialist_review_received' => 'DariaBeauty - Review nou de la {client}! Nota: {rating}/5. Verifica pe site.',
        'verification_code' => 'Codul tau de verificare DariaBeauty: {code}. Valabil 10 minute.',
        'password_reset' => 'Cod resetare parola DariaBeauty: {code}. Valabil 15 minute.',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | SMS Settings
    |--------------------------------------------------------------------------
    */
    
    'country_code' => '+40', // Romania
    
    'rate_limit' => [
        'per_user' => 10, // Max SMS per user per day
        'verification' => 3, // Max verification codes per hour
    ],
];
