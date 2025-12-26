<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Afiseaza toate programarile clientului
     */
    public function index()
    {
        $user = Auth::user();
        
        $appointments = $user->clientAppointments()
                           ->with(['specialist', 'service', 'review'])
                           ->latest('appointment_date')
                           ->paginate(10);

        // Statistici pentru client
        $stats = [
            'total' => $user->clientAppointments()->count(),
            'upcoming' => $user->clientAppointments()->upcoming()->count(),
            'completed' => $user->clientAppointments()->completed()->count(),
            'cancelled' => $user->clientAppointments()->cancelled()->count()
        ];

        return view('appointments.index', compact('appointments', 'stats'));
    }

    /**
     * Creaza o programare noua
     */
    public function create(Request $request)
    {
        $specialist = User::where('role', 'specialist')
                         ->where('is_active', true)
                         ->findOrFail($request->specialist_id);

        $service = Service::where('user_id', $specialist->id)
                         ->findOrFail($request->service_id);

        return view('appointments.create', compact('specialist', 'service'));
    }

    /**
     * Salveaza programarea
     */
    public function store(Request $request)
    {
        $request->validate([
            'specialist_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'appointment_time' => 'required|date_format:H:i',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'required|email|max:255',
            'client_address' => 'required|string',
            'special_requests' => 'nullable|string|max:500'
        ]);

        $specialist = User::findOrFail($request->specialist_id);
        $service = Service::findOrFail($request->service_id);

        // ========== VERIFICARE DISPONIBILITATE ==========
        // Verificăm dacă ora solicitată nu se suprapune cu programările existente
        $requestedDate = Carbon::parse($request->appointment_date);
        $requestedTime = Carbon::parse($request->appointment_time);
        $requestedStart = $requestedDate->copy()->setTimeFromTimeString($request->appointment_time);
        $requestedEnd = $requestedStart->copy()->addMinutes($service->duration);
        
        // Obține toate programările active pentru acest specialist în ziua respectivă
        $existingAppointments = Appointment::where('specialist_id', $specialist->id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('service')
            ->get();
        
        // Verifică suprapunerea
        $isOverlapping = false;
        foreach ($existingAppointments as $existing) {
            $existingStart = Carbon::parse($existing->appointment_date->format('Y-m-d') . ' ' . $existing->appointment_time);
            // Folosește durata din programare sau durata serviciului ca fallback
            $duration = $existing->duration ?? ($existing->service->duration ?? 60);
            $existingEnd = $existingStart->copy()->addMinutes($duration);
            
            // Verifică dacă intervalele se suprapun
            if ($requestedStart->lt($existingEnd) && $requestedEnd->gt($existingStart)) {
                $isOverlapping = true;
                break;
            }
        }
        
        if ($isOverlapping) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['appointment_time' => 'Această oră nu mai este disponibilă. Specialistul are deja o programare în intervalul selectat. Vă rugăm alegeți altă oră.']);
        }
        // ========== SFÂRȘIT VERIFICARE DISPONIBILITATE ==========

        // Calculeaza distanta si taxa transport (simulat)
        $distance = $this->calculateDistance($specialist, $request->client_address);
        $transportFee = $this->calculateTransportFee($specialist, $distance);

        // Timpul estimat de calatorie
        $estimatedTravelTime = $this->calculateTravelTime($distance);

        $appointment = new Appointment([
            'user_id' => Auth::id(),
            'specialist_id' => $specialist->id,
            'service_id' => $service->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'duration' => $service->duration,
            'price' => $service->price,
            'transport_fee' => $transportFee,
            'total_amount' => $service->price + $transportFee,
            'client_name' => $request->client_name,
            'client_phone' => $request->client_phone,
            'client_email' => $request->client_email,
            'client_address' => $request->client_address,
            'distance_km' => $distance,
            'estimated_travel_time' => $estimatedTravelTime,
            'special_requests' => $request->special_requests,
            'status' => 'pending'
        ]);

        $appointment->save();

        // Incarca relatia service pentru notificare
        $appointment->load('service');

        // Log pentru debugging
        \Log::info('Attempting to notify specialist', [
            'specialist_id' => $specialist->id,
            'specialist_phone' => $specialist->phone,
            'appointment_id' => $appointment->id,
            'service_name' => $appointment->service->name ?? 'N/A'
        ]);

        // Notificare catre specialist prin SMS
        $this->notifySpecialist($specialist, $appointment, 'new_appointment');

        return redirect()->route('appointments.show', $appointment)
                        ->with('success', 'Programarea a fost creata cu succes! Vei primi confirmare in curand.');
    }

    /**
     * Afiseaza detaliile unei programari
     */
    public function show($appointment_id)
    {
        // Gaseste programarea DOAR daca apartine user-ului curent
        $appointment = Appointment::where('id', $appointment_id)
                                  ->where(function($query) {
                                      $query->where('user_id', Auth::id())
                                            ->orWhere('specialist_id', Auth::id());
                                  })
                                  ->firstOrFail();

        $appointment->load(['specialist', 'service', 'review']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Confirma programarea (doar specialist)
     */
    public function confirm($appointment_id)
    {
        // Gaseste programarea DOAR daca apartine specialistului curent
        $appointment = Appointment::where('id', $appointment_id)
                                  ->where('specialist_id', Auth::id())
                                  ->firstOrFail();

        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Incarca relatia service pentru notificare
        $appointment->load('service');

        // Notificare catre client
        $this->notifyClient($appointment, 'confirmed');

        return redirect()->back()->with('success', 'Programarea a fost confirmata!');
    }

    /**
     * Anuleaza programarea
     */
    public function cancel(Request $request, $appointment_id)
    {
        // Gaseste programarea DOAR daca apartine user-ului curent (client sau specialist)
        $appointment = Appointment::where('id', $appointment_id)
                                  ->where(function($query) {
                                      $query->where('user_id', Auth::id())
                                            ->orWhere('specialist_id', Auth::id());
                                  })
                                  ->firstOrFail();

        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_by' => Auth::id()
        ]);

        // Notificari
        if (Auth::id() === $appointment->user_id) {
            $this->notifySpecialist($appointment->specialist, $appointment, 'cancelled_by_client');
        } else {
            $this->notifyClient($appointment, 'cancelled_by_specialist');
        }

        return redirect()->back()->with('success', 'Programarea a fost anulata.');
    }

    /**
     * Marcheaza programarea ca finalizata (doar specialist)
     */
    public function complete($appointment_id)
    {
        // Gaseste programarea DOAR daca apartine specialistului curent
        $appointment = Appointment::where('id', $appointment_id)
                                  ->where('specialist_id', Auth::id())
                                  ->firstOrFail();
        
        if (false) {
            abort(403);
        }

        $appointment->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Notificare catre client pentru review
        $this->notifyClient($appointment, 'completed');

        return redirect()->back()->with('success', 'Programarea a fost marcata ca finalizata!');
    }

    /**
     * Trimite link review prin WhatsApp (manual de către specialist)
     */
    public function sendReviewWhatsApp($appointment_id)
    {
        // Gaseste programarea DOAR daca apartine specialistului curent
        $appointment = Appointment::where('id', $appointment_id)
                                  ->where('specialist_id', Auth::id())
                                  ->firstOrFail();
        
        // Verifică că programarea este completed
        if ($appointment->status !== 'completed') {
            return redirect()->back()->with('error', 'Doar programările finalizate pot primi link de review!');
        }

        // Generează token dacă nu există
        if (!$appointment->review_token) {
            $appointment->generateReviewToken();
        }

        $smsService = app(\App\Services\SmsService::class);
        
        try {
            // Build review link
            $reviewLink = url("/review/{$appointment->review_token}");

            // Mesaj WhatsApp
            $message = "Bună {$appointment->client_name}! 🎉\n\n";
            $message .= "Mulțumim că ai ales DariaBeauty!\n\n";
            $message .= "Ne-ar face plăcere să ne lași un review:\n";
            $message .= $reviewLink . "\n\n";
            $message .= "Echipa DariaBeauty ❤️";
            
            // Încearcă WhatsApp mai întâi
            $result = $smsService->sendWhatsApp(
                $appointment->client_phone,
                $message,
                'manual_review_whatsapp',
                $appointment->id,
                $appointment->user_id
            );
            
            if ($result) {
                return redirect()->back()->with('success', 'Link-ul de review a fost trimis prin WhatsApp!');
            }
            
            // Fallback la SMS dacă WhatsApp nu merge
            \Log::info("WhatsApp failed, trying SMS");
            $smsMessage = "Multumim {$appointment->client_name}! Ne-ar face placere sa ne lasi un review. Vei primi detalii pe email.";
            
            $smsResult = $smsService->send(
                $appointment->client_phone,
                $smsMessage,
                'manual_review_sms',
                $appointment->id,
                $appointment->user_id
            );
            
            if ($smsResult) {
                return redirect()->back()->with('warning', 'WhatsApp indisponibil. Am trimis SMS (fără link din cauza restricțiilor operatorului).');
            }
            
            return redirect()->back()->with('error', 'Nu s-a putut trimite notificarea. Verifică numărul de telefon.');
            
        } catch (\Exception $e) {
            \Log::error("Failed to send review WhatsApp", [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Eroare la trimiterea notificării: ' . $e->getMessage());
        }
    }

    /**
     * Generează link de review și îl returnează (pentru copiere manuală)
     */
    public function generateReviewLink($appointment_id)
    {
        try {
            // Gaseste programarea DOAR daca apartine specialistului curent
            $appointment = Appointment::where('id', $appointment_id)
                                      ->where('specialist_id', Auth::id())
                                      ->firstOrFail();
            
            // Verifică că programarea este completed
            if ($appointment->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Doar programările finalizate pot primi link de review!'
                ], 400);
            }

            // Generează token dacă nu există
            if (!$appointment->review_token) {
                $appointment->generateReviewToken();
            }

            // Build review link
            $reviewLink = url("/review/{$appointment->review_token}");

            return response()->json([
                'success' => true,
                'link' => $reviewLink,
                'token' => $appointment->review_token,
                'client_name' => $appointment->client_name
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to generate review link", [
                'appointment_id' => $appointment_id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Eroare la generarea link-ului: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rescheduleaza programarea
     */
    public function reschedule(Request $request, $appointment_id)
    {
        // Gaseste programarea DOAR daca apartine user-ului curent
        $appointment = Appointment::where('id', $appointment_id)
                                  ->where(function($query) {
                                      $query->where('user_id', Auth::id())
                                            ->orWhere('specialist_id', Auth::id());
                                  })
                                  ->firstOrFail();

        $request->validate([
            'new_date' => 'required|date|after:now',
            'new_time' => 'required|date_format:H:i',
            'reschedule_reason' => 'nullable|string|max:500'
        ]);

        $appointment->update([
            'appointment_date' => $request->new_date,
            'appointment_time' => $request->new_time,
            'status' => 'pending',
            'reschedule_reason' => $request->reschedule_reason,
            'rescheduled_at' => now(),
            'rescheduled_by' => Auth::id()
        ]);

        // Notificari
        if (Auth::id() === $appointment->user_id) {
            $this->notifySpecialist($appointment->specialist, $appointment, 'rescheduled_by_client');
        } else {
            $this->notifyClient($appointment, 'rescheduled_by_specialist');
        }

        return redirect()->back()->with('success', 'Programarea a fost reprogramata cu succes!');
    }

    /**
     * Calendar disponibilitate specialist
     */
    public function availability($specialistId, Request $request)
    {
        $specialist = User::where('role', 'specialist')
                         ->where('is_active', true)
                         ->findOrFail($specialistId);

        $date = $request->get('date', now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        // Programari existente pentru acea zi
        $existingAppointments = $specialist->appointments()
                                         ->whereDate('appointment_date', $date)
                                         ->whereIn('status', ['confirmed', 'pending'])
                                         ->get();

        // Genereaza sloturile disponibile
        $availableSlots = $this->generateAvailableSlots($selectedDate, $existingAppointments, $specialist);

        return response()->json([
            'date' => $date,
            'specialist' => $specialist->name,
            'available_slots' => $availableSlots
        ]);
    }

    /**
     * Calculeaza distanta (simulat)
     */
    private function calculateDistance($specialist, $clientAddress)
    {
        // Aici ar fi integrare cu Google Maps API sau similar
        // Pentru demo, returnam o distanta aleatoare
        return rand(5, 50); // km
    }

    /**
     * Calculeaza taxa de transport
     */
    private function calculateTransportFee($specialist, $distance)
    {
        $baseFee = $specialist->transport_fee ?? 20; // Taxa de baza
        $perKmFee = 2; // 2 lei per km

        if ($distance <= 10) {
            return $baseFee;
        }

        return $baseFee + (($distance - 10) * $perKmFee);
    }

    /**
     * Calculeaza timpul estimat de calatorie
     */
    private function calculateTravelTime($distance)
    {
        // Viteza medie in oras: 25 km/h
        return round(($distance / 25) * 60); // minute
    }

    /**
     * Genereaza sloturile disponibile pentru o zi
     */
    private function generateAvailableSlots($date, $existingAppointments, $specialist)
    {
        $slots = [];
        $startHour = 9; // 09:00
        $endHour = 19;  // 19:00
        $slotDuration = 30; // 30 minute

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += $slotDuration) {
                $slotTime = sprintf('%02d:%02d', $hour, $minute);
                $slotDateTime = $date->copy()->setTimeFromTimeString($slotTime);

                // Verifica daca slotul este in trecut
                if ($slotDateTime->isPast()) {
                    continue;
                }

                // Verifica daca slotul este ocupat
                $isOccupied = $existingAppointments->contains(function ($appointment) use ($slotTime) {
                    $appointmentTime = Carbon::parse($appointment->appointment_time);
                    $appointmentEnd = $appointmentTime->copy()->addMinutes($appointment->duration);
                    $slotStart = Carbon::parse($slotTime);
                    $slotEnd = $slotStart->copy()->addMinutes(30);

                    return $slotStart->between($appointmentTime, $appointmentEnd) ||
                           $slotEnd->between($appointmentTime, $appointmentEnd) ||
                           $appointmentTime->between($slotStart, $slotEnd);
                });

                if (!$isOccupied) {
                    $slots[] = [
                        'time' => $slotTime,
                        'formatted_time' => $slotDateTime->format('H:i'),
                        'available' => true
                    ];
                }
            }
        }

        return $slots;
    }

    /**
     * Notifica specialistul
     */
    private function notifySpecialist($specialist, $appointment, $type = 'new_appointment')
    {
        \Log::info("=== NOTIFY SPECIALIST START ===", [
            'specialist_id' => $specialist->id,
            'specialist_phone' => $specialist->phone,
            'appointment_id' => $appointment->id,
            'type' => $type
        ]);

        // Trimite SMS daca este activat
        $smsService = app(\App\Services\SmsService::class);
        
        try {
            switch($type) {
                case 'new_appointment':
                    \Log::info("Calling notifySpecialistNewAppointment");
                    $result = $smsService->notifySpecialistNewAppointment($appointment, $specialist);
                    \Log::info("SMS Result: " . ($result ? 'SUCCESS' : 'FAILED'));
                    break;
                case 'cancelled_by_client':
                    $smsService->notifySpecialistCancellation($appointment, $specialist);
                    break;
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send SMS notification to specialist", [
                'specialist_id' => $specialist->id,
                'appointment_id' => $appointment->id,
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        \Log::info("=== NOTIFY SPECIALIST END ===");
    }

    /**
     * Notifica clientul
     */
    private function notifyClient($appointment, $type)
    {
        \Log::info("=== NOTIFY CLIENT START ===", [
            'appointment_id' => $appointment->id,
            'client_phone' => $appointment->client_phone,
            'type' => $type
        ]);

        // Trimite SMS daca este activat
        $smsService = app(\App\Services\SmsService::class);
        
        try {
            switch($type) {
                case 'confirmed':
                    \Log::info("Calling sendAppointmentConfirmation");
                    $result = $smsService->sendAppointmentConfirmation($appointment);
                    \Log::info("SMS Result: " . ($result ? 'SUCCESS' : 'FAILED'));
                    break;
                case 'cancelled_by_specialist':
                    $smsService->sendAppointmentCancellation($appointment);
                    break;
                case 'completed':
                    // Trimite SMS cu request pentru review
                    \Log::info("Sending appointment completed notification with review request", ['appointment_id' => $appointment->id]);
                    $result = $smsService->sendAppointmentCompletedWithReview($appointment);
                    \Log::info("SMS Review Request Result: " . ($result ? 'SUCCESS' : 'FAILED'));
                    break;
                case 'rescheduled_by_specialist':
                    $smsService->sendAppointmentConfirmation($appointment);
                    break;
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send SMS notification", [
                'appointment_id' => $appointment->id,
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        \Log::info("=== NOTIFY CLIENT END ===");
    }
    
    /**
     * Afișează programările clientului autentificat
     */
    public function clientAppointments()
    {
        $user = auth()->user();
        
        $appointments = Appointment::where('client_email', $user->email)
            ->with(['specialist', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);
        
        // Statistici
        $stats = [
            'total' => Appointment::where('client_email', $user->email)->count(),
            'pending' => Appointment::where('client_email', $user->email)->where('status', 'pending')->count(),
            'confirmed' => Appointment::where('client_email', $user->email)->where('status', 'confirmed')->count(),
            'completed' => Appointment::where('client_email', $user->email)->where('status', 'completed')->count(),
            'cancelled' => Appointment::where('client_email', $user->email)->where('status', 'cancelled')->count(),
        ];
        
        return view('client.appointments', compact('appointments', 'stats'));
    }
}