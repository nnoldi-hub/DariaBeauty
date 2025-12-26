<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalonReportsController extends Controller
{
    /**
     * Dashboard principal cu statistici pentru salon
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Perioada de analiză (default: ultima lună)
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Determină dacă e salon owner sau specialist individual
        $isSalonOwner = ($user->role === 'salon' || $user->is_salon_owner);
        
        if ($isSalonOwner) {
            // Salon owner: vede toți specialiștii din salon
            $specialists = User::where('salon_id', $user->id)
                ->where('role', 'specialist')
                ->where('is_active', true)
                ->get();
            $specialistIds = $specialists->pluck('id')->toArray();
            
            // Dacă nu are specialiști, include doar pe el
            if (empty($specialistIds)) {
                $specialistIds = [$user->id];
            }
        } else {
            // Specialist individual: vede doar propriile rapoarte
            $specialistIds = [$user->id];
            $specialists = collect([$user]);
        }

        // 1. STATISTICI GENERALE
        $stats = [
            'total_appointments' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->count(),
            
            'completed_appointments' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->count(),
            
            'pending_appointments' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'pending')
                ->count(),
            
            'cancelled_appointments' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'cancelled')
                ->count(),
            
            'total_revenue' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('total_amount'),
            
            'avg_appointment_value' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->avg('total_amount'),
        ];

        // No-show rate
        $stats['noshow_rate'] = $stats['total_appointments'] > 0 
            ? round(($stats['cancelled_appointments'] / $stats['total_appointments']) * 100, 1)
            : 0;

        // 2. TOP SERVICII (cele mai rezervate)
        $topServices = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select('service_id', DB::raw('COUNT(*) as total_bookings'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('service_id')
            ->orderBy('total_bookings', 'DESC')
            ->limit(10)
            ->with('service')
            ->get();

        // 3. PERFORMANCE SPECIALIȘTI
        $specialistPerformance = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select(
                'specialist_id',
                DB::raw('COUNT(*) as total_appointments'),
                DB::raw('COUNT(CASE WHEN status = "completed" THEN 1 END) as completed'),
                DB::raw('COUNT(CASE WHEN status = "cancelled" THEN 1 END) as cancelled'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as revenue')
            )
            ->groupBy('specialist_id')
            ->with('specialist')
            ->orderBy('revenue', 'DESC')
            ->get();

        // 4. PROGRAMĂRI PE ZILE (pentru grafic)
        $appointmentsByDay = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // 5. PROGRAMĂRI PE ORĂ (identifică ore moarte)
        $appointmentsByHour = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select(
                DB::raw('HOUR(appointment_time) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get();

        // 6. PROGRAMĂRI PE ZIUA SĂPTĂMÂNII
        $appointmentsByWeekday = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select(
                DB::raw('DAYOFWEEK(appointment_date) as weekday'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as revenue')
            )
            ->groupBy('weekday')
            ->orderBy('weekday', 'ASC')
            ->get();

        // Mapare zile
        $weekdayNames = [
            1 => 'Duminică',
            2 => 'Luni',
            3 => 'Marți',
            4 => 'Miercuri',
            5 => 'Joi',
            6 => 'Vineri',
            7 => 'Sâmbătă'
        ];

        // 7. PREDICȚIE SĂPTĂMÂNA VIITOARE (bazat pe media ultimelor 4 săptămâni)
        $nextWeekStart = Carbon::now()->startOfWeek()->addWeek();
        $nextWeekEnd = Carbon::now()->endOfWeek()->addWeek();
        
        $avgWeeklyAppointments = Appointment::whereIn('specialist_id', $specialistIds)
            ->where('appointment_date', '>=', Carbon::now()->subWeeks(4))
            ->where('appointment_date', '<', Carbon::now())
            ->count() / 4;

        $prediction = [
            'next_week_start' => $nextWeekStart->format('d M Y'),
            'next_week_end' => $nextWeekEnd->format('d M Y'),
            'estimated_appointments' => round($avgWeeklyAppointments),
            'estimated_revenue' => round($avgWeeklyAppointments * $stats['avg_appointment_value']),
        ];

        // 8. CLIENȚI NOI vs RECURENȚI
        $newVsReturning = DB::table('appointments as a1')
            ->whereIn('a1.specialist_id', $specialistIds)
            ->whereBetween('a1.appointment_date', [$startDate, $endDate])
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN (
                    SELECT COUNT(*) 
                    FROM appointments a2 
                    WHERE a2.client_name = a1.client_name 
                    AND a2.client_phone = a1.client_phone
                    AND a2.appointment_date < a1.appointment_date
                ) = 0 THEN 1 ELSE 0 END) as new_clients'),
                DB::raw('SUM(CASE WHEN (
                    SELECT COUNT(*) 
                    FROM appointments a2 
                    WHERE a2.client_name = a1.client_name 
                    AND a2.client_phone = a1.client_phone
                    AND a2.appointment_date < a1.appointment_date
                ) > 0 THEN 1 ELSE 0 END) as returning_clients')
            )
            ->first();

        return view('salon.reports.index', compact(
            'stats',
            'topServices',
            'specialistPerformance',
            'appointmentsByDay',
            'appointmentsByHour',
            'appointmentsByWeekday',
            'weekdayNames',
            'prediction',
            'newVsReturning',
            'startDate',
            'endDate',
            'specialists',
            'isSalonOwner'
        ));
    }

    /**
     * Export raport CSV
     */
    public function exportCSV(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Determină dacă e salon owner
        $isSalonOwner = ($user->role === 'salon' || $user->is_salon_owner);
        
        if ($isSalonOwner) {
            // Salon owner: exportă toți specialiștii din salon
            $specialistIds = User::where('salon_id', $user->id)
                ->where('role', 'specialist')
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();
            
            if (empty($specialistIds)) {
                $specialistIds = [$user->id];
            }
        } else {
            // Specialist individual
            $specialistIds = [$user->id];
        }

        $appointments = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->with(['specialist', 'service'])
            ->orderBy('appointment_date', 'DESC')
            ->get();

        $filename = "raport_salon_" . $startDate . "_to_" . $endDate . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            
            // BOM pentru UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, [
                'Data',
                'Ora',
                'Specialist',
                'Client',
                'Telefon',
                'Serviciu',
                'Preț',
                'Locație',
                'Status'
            ]);

            // Data
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    Carbon::parse($appointment->appointment_date)->format('d.m.Y'),
                    $appointment->appointment_time,
                    $appointment->specialist->name ?? 'N/A',
                    $appointment->client_name,
                    $appointment->client_phone,
                    $appointment->service->name ?? 'N/A',
                    $appointment->total_amount . ' lei',
                    $appointment->location === 'salon' ? 'La salon' : 'La domiciliu',
                    ucfirst($appointment->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Raport detaliat pentru un specialist
     */
    public function specialistDetail($specialist_id, Request $request)
    {
        $user = Auth::user();
        
        // Verifică permisiunile
        $isSalonOwner = ($user->role === 'salon' || $user->is_salon_owner);
        
        if ($isSalonOwner) {
            // Salon owner: poate vedea orice specialist din salon
            $specialist = User::where('id', $specialist_id)
                ->where(function($query) use ($user) {
                    $query->where('salon_id', $user->id)
                          ->orWhere('id', $user->id);
                })
                ->firstOrFail();
        } else {
            // Specialist individual: doar propriile date
            if ($specialist_id != $user->id) {
                abort(403, 'Nu ai permisiunea să vezi raportul acestui specialist.');
            }
            $specialist = $user;
        }

        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Statistici generale pentru acest specialist
        $stats = [
            'total_appointments' => Appointment::where('specialist_id', $specialist_id)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->count(),
            'completed' => Appointment::where('specialist_id', $specialist_id)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->count(),
            'pending' => Appointment::where('specialist_id', $specialist_id)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'pending')
                ->count(),
            'cancelled' => Appointment::where('specialist_id', $specialist_id)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'cancelled')
                ->count(),
            'total_revenue' => Appointment::where('specialist_id', $specialist_id)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->sum('total_amount'),
            'avg_appointment_value' => Appointment::where('specialist_id', $specialist_id)
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->avg('total_amount'),
        ];

        // No-show rate
        $stats['noshow_rate'] = $stats['total_appointments'] > 0 
            ? round(($stats['cancelled'] / $stats['total_appointments']) * 100, 1)
            : 0;

        // Top servicii pentru acest specialist
        $topServices = Appointment::where('specialist_id', $specialist_id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select('service_id', DB::raw('COUNT(*) as total_bookings'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('service_id')
            ->orderBy('total_bookings', 'DESC')
            ->limit(10)
            ->with('service')
            ->get();

        // Programări pe ziua săptămânii
        $appointmentsByWeekday = Appointment::where('specialist_id', $specialist_id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select(
                DB::raw('DAYOFWEEK(appointment_date) as weekday'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('weekday')
            ->orderBy('weekday', 'ASC')
            ->get();

        $weekdayNames = [
            1 => 'Duminică',
            2 => 'Luni',
            3 => 'Marți',
            4 => 'Miercuri',
            5 => 'Joi',
            6 => 'Vineri',
            7 => 'Sâmbătă'
        ];

        // Programări pe oră
        $appointmentsByHour = Appointment::where('specialist_id', $specialist_id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select(
                DB::raw('HOUR(appointment_time) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get();

        // Programări pe zi (pentru grafic timeline)
        $appointmentsByDay = Appointment::where('specialist_id', $specialist_id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Ultimele 20 programări
        $recentAppointments = Appointment::where('specialist_id', $specialist_id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->with(['service'])
            ->orderBy('appointment_date', 'DESC')
            ->orderBy('appointment_time', 'DESC')
            ->limit(20)
            ->get();

        return view('salon.reports.specialist-detail', compact(
            'specialist',
            'stats',
            'topServices',
            'appointmentsByWeekday',
            'weekdayNames',
            'appointmentsByHour',
            'appointmentsByDay',
            'recentAppointments',
            'startDate',
            'endDate'
        ));
    }
}
