<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalonDashboardController extends Controller
{
    /**
     * Dashboard pentru salon owners
     */
    public function index()
    {
        $user = Auth::user();
        
        // Verifică că e salon owner
        if (!($user->role === 'salon' || $user->is_salon_owner)) {
            // Dacă e specialist normal, redirect la dashboard specialist
            return redirect()->route('specialist.dashboard');
        }

        // Specialiștii din salon
        $specialists = User::where('salon_id', $user->id)
            ->where('role', 'specialist')
            ->where('is_active', true)
            ->get();

        $specialistIds = $specialists->pluck('id')->toArray();
        
        // Dacă nu are specialiști, include doar pe el
        if (empty($specialistIds)) {
            $specialistIds = [$user->id];
        }

        // Statistici generale
        $stats = [
            'total_specialists' => $specialists->count(),
            
            // Astăzi
            'total_appointments_today' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereDate('appointment_date', Carbon::today())
                ->count(),
            
            'pending_appointments' => Appointment::whereIn('specialist_id', $specialistIds)
                ->where('status', 'pending')
                ->count(),
            
            'revenue_today' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereDate('appointment_date', Carbon::today())
                ->where('status', 'completed')
                ->sum('total_amount'),
            
            // Luna curentă
            'appointments_this_month' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereMonth('appointment_date', Carbon::now()->month)
                ->whereYear('appointment_date', Carbon::now()->year)
                ->count(),
            
            'revenue_this_month' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereMonth('appointment_date', Carbon::now()->month)
                ->whereYear('appointment_date', Carbon::now()->year)
                ->where('status', 'completed')
                ->sum('total_amount'),
            
            'avg_appointment_value' => Appointment::whereIn('specialist_id', $specialistIds)
                ->whereMonth('appointment_date', Carbon::now()->month)
                ->whereYear('appointment_date', Carbon::now()->year)
                ->where('status', 'completed')
                ->avg('total_amount') ?? 0,
        ];

        // Creștere vs luna trecută
        $lastMonthAppointments = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereMonth('appointment_date', Carbon::now()->subMonth()->month)
            ->whereYear('appointment_date', Carbon::now()->subMonth()->year)
            ->count();

        $lastMonthRevenue = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereMonth('appointment_date', Carbon::now()->subMonth()->month)
            ->whereYear('appointment_date', Carbon::now()->subMonth()->year)
            ->where('status', 'completed')
            ->sum('total_amount');

        $stats['appointments_growth'] = $lastMonthAppointments > 0 
            ? round((($stats['appointments_this_month'] - $lastMonthAppointments) / $lastMonthAppointments) * 100, 1)
            : 0;

        $stats['revenue_growth'] = $lastMonthRevenue > 0
            ? round((($stats['revenue_this_month'] - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // Top performeri luna aceasta
        $topPerformers = User::whereIn('id', $specialistIds)
            ->withCount([
                'appointments as total_appointments' => function($query) {
                    $query->whereMonth('appointment_date', Carbon::now()->month)
                          ->whereYear('appointment_date', Carbon::now()->year);
                },
                'appointments as completed_appointments' => function($query) {
                    $query->whereMonth('appointment_date', Carbon::now()->month)
                          ->whereYear('appointment_date', Carbon::now()->year)
                          ->where('status', 'completed');
                }
            ])
            ->withSum([
                'appointments as total_revenue' => function($query) {
                    $query->whereMonth('appointment_date', Carbon::now()->month)
                          ->whereYear('appointment_date', Carbon::now()->year)
                          ->where('status', 'completed');
                }
            ], 'total_amount')
            ->withAvg([
                'reviews as avg_rating' => function($query) {
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                }
            ], 'rating')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Programări astăzi
        $todayAppointments = Appointment::whereIn('specialist_id', $specialistIds)
            ->whereDate('appointment_date', Carbon::today())
            ->with(['specialist', 'service'])
            ->orderBy('appointment_time', 'ASC')
            ->get();

        // Evoluție ultimele 7 zile
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $weeklyData[] = [
                'date' => $date->format('Y-m-d'),
                'appointments' => Appointment::whereIn('specialist_id', $specialistIds)
                    ->whereDate('appointment_date', $date)
                    ->count(),
                'revenue' => Appointment::whereIn('specialist_id', $specialistIds)
                    ->whereDate('appointment_date', $date)
                    ->where('status', 'completed')
                    ->sum('total_amount')
            ];
        }

        return view('salon.dashboard', compact(
            'specialists',
            'stats',
            'topPerformers',
            'todayAppointments',
            'weeklyData'
        ));
    }
}
