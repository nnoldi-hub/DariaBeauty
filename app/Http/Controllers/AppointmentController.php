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

        // Notificare catre specialist (aici ar fi email/SMS)
        $this->notifySpecialist($specialist, $appointment);

        return redirect()->route('appointments.show', $appointment)
                        ->with('success', 'Programarea a fost creata cu succes! Vei primi confirmare in curand.');
    }

    /**
     * Afiseaza detaliile unei programari
     */
    public function show(Appointment $appointment)
    {
        // Verifica daca userul poate vedea aceasta programare
        if (Auth::id() !== $appointment->user_id && Auth::id() !== $appointment->specialist_id) {
            abort(403);
        }

        $appointment->load(['specialist', 'service', 'review']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Confirma programarea (doar specialist)
     */
    public function confirm(Appointment $appointment)
    {
        if (Auth::id() !== $appointment->specialist_id) {
            abort(403);
        }

        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Notificare catre client
        $this->notifyClient($appointment, 'confirmed');

        return redirect()->back()->with('success', 'Programarea a fost confirmata!');
    }

    /**
     * Anuleaza programarea
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        // Verifica daca userul poate anula aceasta programare
        if (Auth::id() !== $appointment->user_id && Auth::id() !== $appointment->specialist_id) {
            abort(403);
        }

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
    public function complete(Appointment $appointment)
    {
        if (Auth::id() !== $appointment->specialist_id) {
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
     * Rescheduleaza programarea
     */
    public function reschedule(Request $request, Appointment $appointment)
    {
        // Verifica daca userul poate reprograma
        if (Auth::id() !== $appointment->user_id && Auth::id() !== $appointment->specialist_id) {
            abort(403);
        }

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
        // Aici ar fi logica pentru email/SMS/push notifications
        // Pentru demo, doar logam
        \Log::info("Notification sent to specialist {$specialist->id}: {$type}");
    }

    /**
     * Notifica clientul
     */
    private function notifyClient($appointment, $type)
    {
        // Aici ar fi logica pentru email/SMS/push notifications
        // Pentru demo, doar logam
        \Log::info("Notification sent to client {$appointment->user_id}: {$type}");
    }
}