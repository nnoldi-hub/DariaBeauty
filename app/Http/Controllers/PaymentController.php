<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        // Logica pentru procesarea plăților
        return view('payment.process');
    }

    public function success()
    {
        return view('payment.success');
    }

    public function cancel()
    {
        return view('payment.cancel');
    }

    public function webhook(Request $request)
    {
        // Logica pentru webhook-uri de plată
        return response()->json(['status' => 'ok']);
    }

    // Endpoint specific pentru Stripe folosit în rute (compatibilitate)
    public function stripeWebhook(Request $request)
    {
        return $this->webhook($request);
    }
}