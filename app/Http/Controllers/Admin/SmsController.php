<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display SMS settings and statistics
     */
    public function index()
    {
        $stats = [
            'total_sent' => SmsLog::sent()->count(),
            'total_failed' => SmsLog::failed()->count(),
            'today_sent' => SmsLog::sent()->whereDate('created_at', today())->count(),
            'this_month' => SmsLog::sent()->whereMonth('created_at', now()->month)->count(),
        ];

        $recentLogs = SmsLog::with(['user', 'appointment'])
            ->latest()
            ->limit(50)
            ->get();

        $isEnabled = $this->smsService->isEnabled();
        $isConfigured = !empty(config('twilio.sid')) && !empty(config('twilio.auth_token'));

        return view('admin.sms.index', compact('stats', 'recentLogs', 'isEnabled', 'isConfigured'));
    }

    /**
     * Update Twilio configuration
     */
    public function updateConfig(Request $request)
    {
        $request->validate([
            'twilio_sid' => 'required|string',
            'twilio_auth_token' => 'required|string',
            'twilio_phone_number' => 'required|string',
            'twilio_enabled' => 'required|boolean',
        ]);

        // Update .env file
        $this->updateEnvFile([
            'TWILIO_SID' => $request->twilio_sid,
            'TWILIO_AUTH_TOKEN' => $request->twilio_auth_token,
            'TWILIO_PHONE_NUMBER' => $request->twilio_phone_number,
            'TWILIO_ENABLED' => $request->twilio_enabled ? 'true' : 'false',
        ]);

        // Clear config cache
        Artisan::call('config:clear');

        return redirect()->route('admin.sms.index')
            ->with('success', 'Configurația Twilio a fost actualizată cu succes!');
    }

    /**
     * Send test SMS
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $sent = $this->smsService->send($request->phone, $request->message);

        if ($sent) {
            return redirect()->route('admin.sms.index')
                ->with('success', 'SMS de test trimis cu succes!');
        }

        return redirect()->route('admin.sms.index')
            ->with('error', 'Eroare la trimiterea SMS-ului de test. Verifică log-urile.');
    }

    /**
     * View SMS log details
     */
    public function show(SmsLog $smsLog)
    {
        $smsLog->load(['user', 'appointment']);
        return view('admin.sms.show', compact('smsLog'));
    }

    /**
     * Trigger manual reminder send
     */
    public function sendReminders()
    {
        Artisan::call('appointments:send-reminders');
        $output = Artisan::output();

        return redirect()->route('admin.sms.index')
            ->with('success', 'Comenzi de reminder executată: ' . $output);
    }

    /**
     * Update environment file
     */
    protected function updateEnvFile(array $data)
    {
        $envFile = base_path('.env');
        $env = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, $replacement, $env);
            } else {
                $env .= "\n{$replacement}";
            }
        }

        file_put_contents($envFile, $env);
    }
}
