<?php

namespace App\Http\Controllers;

use App\Models\SpecialistSchedule;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SpecialistScheduleController extends Controller
{
    /**
     * Afișează pagina de setare program
     */
    public function index()
    {
        $specialist = Auth::user();
        
        // Obține programul existent sau creează unul gol pentru fiecare zi
        $schedules = [];
        $daysOfWeek = SpecialistSchedule::getDaysOfWeek();
        
        foreach ($daysOfWeek as $dayNum => $dayName) {
            $schedule = $specialist->schedules()->where('day_of_week', $dayNum)->first();
            
            if (!$schedule) {
                // Creează un schedule gol pentru această zi
                $schedule = new SpecialistSchedule([
                    'specialist_id' => $specialist->id,
                    'day_of_week' => $dayNum,
                    'available_at_salon' => false,
                    'available_at_home' => false
                ]);
            }
            
            $schedules[$dayNum] = $schedule;
        }
        
        return view('specialist.schedule.index', compact('schedules', 'daysOfWeek', 'specialist'));
    }

    /**
     * Salvează programul de lucru
     */
    public function store(Request $request)
    {
        $specialist = Auth::user();
        
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|between:0,6',
            'slot_interval' => 'nullable|integer|min:15|max:120',
            'min_booking_notice' => 'nullable|integer|min:1|max:72',
            'max_booking_days' => 'nullable|integer|min:1|max:90',
        ]);
        
        // Actualizează setările generale
        $specialist->update([
            'slot_interval' => $request->input('slot_interval', 30),
            'min_booking_notice' => $request->input('min_booking_notice', 2),
            'max_booking_days' => $request->input('max_booking_days', 30),
        ]);
        
        // Salvează programul pentru fiecare zi
        foreach ($request->schedules as $dayData) {
            $dayOfWeek = $dayData['day_of_week'];
            
            // Determină disponibilitatea
            $availableAtSalon = isset($dayData['available_at_salon']) && $dayData['available_at_salon'];
            $availableAtHome = isset($dayData['available_at_home']) && $dayData['available_at_home'];
            
            SpecialistSchedule::updateOrCreate(
                [
                    'specialist_id' => $specialist->id,
                    'day_of_week' => $dayOfWeek
                ],
                [
                    'available_at_salon' => $availableAtSalon,
                    'salon_start_time' => $availableAtSalon ? ($dayData['salon_start_time'] ?? '09:00') : null,
                    'salon_end_time' => $availableAtSalon ? ($dayData['salon_end_time'] ?? '18:00') : null,
                    'available_at_home' => $availableAtHome,
                    'home_start_time' => $availableAtHome ? ($dayData['home_start_time'] ?? '10:00') : null,
                    'home_end_time' => $availableAtHome ? ($dayData['home_end_time'] ?? '20:00') : null,
                    'break_start_time' => !empty($dayData['break_start_time']) ? $dayData['break_start_time'] : null,
                    'break_end_time' => !empty($dayData['break_end_time']) ? $dayData['break_end_time'] : null,
                    'notes' => $dayData['notes'] ?? null
                ]
            );
        }
        
        return redirect()->route('specialist.schedule.index')
                        ->with('success', 'Programul de lucru a fost salvat cu succes!');
    }

    /**
     * API: Obține sloturile disponibile pentru o zi și locație
     */
    public function getAvailableSlots(Request $request, $specialistId)
    {
        $request->validate([
            'date' => 'required|date',
            'location' => 'required|in:salon,home',
            'service_id' => 'required|exists:services,id'
        ]);
        
        $specialist = User::where('role', 'specialist')
                         ->where('is_active', true)
                         ->findOrFail($specialistId);
        
        $date = Carbon::parse($request->date);
        $locationType = $request->location;
        $service = Service::findOrFail($request->service_id);
        $serviceDuration = $service->duration;
        
        // Verifică dacă data este în intervalul permis
        $minDate = Carbon::now()->addHours($specialist->min_booking_notice ?? 2);
        $maxDate = Carbon::now()->addDays($specialist->max_booking_days ?? 30);
        
        if ($date->lt($minDate->startOfDay())) {
            return response()->json([
                'success' => false,
                'message' => 'Data selectată este prea apropiată. Trebuie să rezervi cu minim ' . ($specialist->min_booking_notice ?? 2) . ' ore în avans.',
                'slots' => []
            ]);
        }
        
        if ($date->gt($maxDate)) {
            return response()->json([
                'success' => false,
                'message' => 'Nu poți rezerva cu mai mult de ' . ($specialist->max_booking_days ?? 30) . ' zile în avans.',
                'slots' => []
            ]);
        }
        
        // Obține programul pentru ziua respectivă
        $dayOfWeek = $date->dayOfWeek; // 0 = Duminică, 1 = Luni, etc.
        $schedule = $specialist->getScheduleForDay($dayOfWeek);
        
        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Specialistul nu are program setat pentru această zi.',
                'slots' => []
            ]);
        }
        
        // Verifică disponibilitatea pentru tipul de locație
        $workingHours = $schedule->getWorkingHours($locationType);
        
        if (!$workingHours) {
            $locationText = $locationType === 'salon' ? 'la salon' : 'la domiciliu';
            return response()->json([
                'success' => false,
                'message' => "Specialistul nu este disponibil {$locationText} în această zi.",
                'slots' => []
            ]);
        }
        
        // Generează sloturile disponibile
        $slots = $this->generateTimeSlots(
            $specialist,
            $date,
            $workingHours['start'],
            $workingHours['end'],
            $schedule,
            $serviceDuration
        );
        
        return response()->json([
            'success' => true,
            'date' => $date->format('Y-m-d'),
            'day_name' => $this->getDayName($dayOfWeek),
            'working_hours' => $workingHours,
            'slots' => $slots,
            'service_duration' => $serviceDuration
        ]);
    }

    /**
     * Generează sloturile de timp disponibile
     */
    private function generateTimeSlots($specialist, $date, $startTime, $endTime, $schedule, $serviceDuration)
    {
        $slots = [];
        $slotInterval = $specialist->slot_interval ?? 30;
        
        $currentTime = Carbon::parse($date->format('Y-m-d') . ' ' . $startTime);
        $endDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $endTime);
        
        // Obține programările existente pentru această zi
        $existingAppointments = Appointment::where('specialist_id', $specialist->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();
        
        // Timpul minim de rezervare (nu poți rezerva în trecut)
        $minBookingTime = Carbon::now()->addHours($specialist->min_booking_notice ?? 2);
        
        while ($currentTime->copy()->addMinutes($serviceDuration)->lte($endDateTime)) {
            $slotStart = $currentTime->copy();
            $slotEnd = $slotStart->copy()->addMinutes($serviceDuration);
            
            // Verifică dacă slotul este în trecut
            if ($slotStart->lt($minBookingTime)) {
                $currentTime->addMinutes($slotInterval);
                continue;
            }
            
            // Verifică dacă slotul este în pauză
            if ($schedule->isInBreak($slotStart->format('H:i'))) {
                $currentTime->addMinutes($slotInterval);
                continue;
            }
            
            // Verifică dacă slotul se suprapune cu programări existente
            $isAvailable = true;
            foreach ($existingAppointments as $appointment) {
                $appointmentStart = Carbon::parse($appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->appointment_time);
                $appointmentDuration = $appointment->duration ?? ($appointment->service->duration ?? 60);
                $appointmentEnd = $appointmentStart->copy()->addMinutes($appointmentDuration);
                
                // Verifică suprapunerea
                if ($slotStart->lt($appointmentEnd) && $slotEnd->gt($appointmentStart)) {
                    $isAvailable = false;
                    break;
                }
            }
            
            if ($isAvailable) {
                $slots[] = [
                    'time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    'formatted' => $slotStart->format('H:i') . ' - ' . $slotEnd->format('H:i'),
                    'available' => true
                ];
            }
            
            $currentTime->addMinutes($slotInterval);
        }
        
        return $slots;
    }

    /**
     * Obține numele zilei în română
     */
    private function getDayName($dayOfWeek): string
    {
        $days = [
            0 => 'Duminică',
            1 => 'Luni',
            2 => 'Marți',
            3 => 'Miercuri',
            4 => 'Joi',
            5 => 'Vineri',
            6 => 'Sâmbătă'
        ];
        
        return $days[$dayOfWeek] ?? '';
    }

    /**
     * API: Obține zilele disponibile pentru următoarele N zile
     */
    public function getAvailableDays(Request $request, $specialistId)
    {
        $specialist = User::where('role', 'specialist')
                         ->where('is_active', true)
                         ->findOrFail($specialistId);
        
        $locationType = $request->input('location', 'salon');
        $days = [];
        
        $startDate = Carbon::now()->addHours($specialist->min_booking_notice ?? 2)->startOfDay();
        $endDate = Carbon::now()->addDays($specialist->max_booking_days ?? 30);
        
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->dayOfWeek;
            $schedule = $specialist->getScheduleForDay($dayOfWeek);
            
            $isAvailable = false;
            if ($schedule) {
                if ($locationType === 'salon' && $schedule->available_at_salon) {
                    $isAvailable = true;
                } elseif ($locationType === 'home' && $schedule->available_at_home) {
                    $isAvailable = true;
                } elseif ($locationType === 'any' && ($schedule->available_at_salon || $schedule->available_at_home)) {
                    $isAvailable = true;
                }
            }
            
            $days[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day_name' => $this->getDayName($dayOfWeek),
                'day_short' => $currentDate->format('D'),
                'day_number' => $currentDate->format('d'),
                'month' => $currentDate->format('M'),
                'available' => $isAvailable,
                'is_today' => $currentDate->isToday(),
                'is_tomorrow' => $currentDate->isTomorrow()
            ];
            
            $currentDate->addDay();
        }
        
        return response()->json([
            'success' => true,
            'days' => $days
        ]);
    }
}
