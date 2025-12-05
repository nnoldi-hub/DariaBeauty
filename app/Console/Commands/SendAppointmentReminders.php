<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send SMS reminders for appointments scheduled in the next 24 hours';

    protected $smsService;

    /**
     * Create a new command instance.
     */
    public function __construct(SmsService $smsService)
    {
        parent::__construct();
        $this->smsService = $smsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->smsService->isEnabled()) {
            $this->warn('SMS service is disabled');
            return 0;
        }

        $this->info('Checking for appointments needing reminders...');

        // Get appointments for tomorrow
        $tomorrow = Carbon::tomorrow();
        $appointments = Appointment::where('appointment_date', '>=', $tomorrow->startOfDay())
            ->where('appointment_date', '<=', $tomorrow->endOfDay())
            ->where('status', 'confirmed')
            ->whereDoesntHave('smsLogs', function ($query) {
                $query->where('type', 'appointment_reminder')
                      ->where('status', 'sent')
                      ->where('created_at', '>=', Carbon::now()->subDay());
            })
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($appointments as $appointment) {
            $this->info("Processing appointment #{$appointment->id} for {$appointment->client_name}");

            if ($this->smsService->sendAppointmentReminder($appointment)) {
                $sent++;
                $this->info("✓ Reminder sent successfully");
            } else {
                $failed++;
                $this->error("✗ Failed to send reminder");
            }
        }

        $this->info("\nSummary:");
        $this->info("Total appointments: {$appointments->count()}");
        $this->info("Sent: {$sent}");
        $this->info("Failed: {$failed}");

        return 0;
    }
}
