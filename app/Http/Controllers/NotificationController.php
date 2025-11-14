<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index');
    }

    public function send(Request $request)
    {
        // Logica pentru trimiterea notificărilor
        return response()->json(['status' => 'sent']);
    }

    public function markAsRead($id)
    {
        // Logica pentru marcarea ca citită
        return response()->json(['status' => 'read']);
    }

    // Endpoint compatibil cu ruta webhook/sms-status
    public function smsStatus(Request $request)
    {
        return response()->json(['status' => 'received']);
    }
}