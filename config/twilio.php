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
        'new_appointment_specialist' => 'Programare noua: {client} pentru {service} pe {date} la {time}. DariaBeauty',
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
