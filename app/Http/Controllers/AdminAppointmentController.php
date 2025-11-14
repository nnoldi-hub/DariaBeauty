<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['specialist', 'service']);
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by brand
        if ($request->filled('brand')) {
            $query->whereHas('specialist', function($q) use ($request) {
                $q->where('sub_brand', $request->brand);
            });
        }
        
        // Search specialist
        if ($request->filled('search')) {
            $query->whereHas('specialist', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $appointments = $query->latest('appointment_date')->paginate(20);
        
        // Statistics
        $today = now()->toDateString();
        $stats = [
            'today' => Appointment::whereDate('appointment_date', $today)->count(),
            'confirmed' => Appointment::whereDate('appointment_date', $today)->where('status', 'confirmed')->count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'cancelled' => Appointment::whereDate('appointment_date', $today)->where('status', 'cancelled')->count(),
        ];
        
        return view('admin.appointments', compact('appointments', 'stats'));
    }
    
    public function export(Request $request)
    {
        $query = Appointment::with(['specialist', 'service']);
        
        // Apply same filters as index
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('brand')) {
            $query->whereHas('specialist', function($q) use ($request) {
                $q->where('sub_brand', $request->brand);
            });
        }
        if ($request->filled('search')) {
            $query->whereHas('specialist', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $appointments = $query->latest('appointment_date')->get();
        
        // Generate CSV
        $filename = 'programari_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'ID',
                'Data',
                'Ora',
                'Client',
                'Email Client',
                'Telefon Client',
                'Specialist',
                'Brand',
                'Serviciu',
                'Status',
                'Pret (RON)',
                'Durata (min)',
                'Creat la'
            ]);
            
            // Data rows
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->id,
                    $appointment->appointment_date ? $appointment->appointment_date->format('d.m.Y') : '',
                    $appointment->appointment_time ?? '',
                    $appointment->client_name ?? 'N/A',
                    $appointment->client_email ?? 'N/A',
                    $appointment->client_phone ?? 'N/A',
                    $appointment->specialist ? $appointment->specialist->name : 'N/A',
                    $appointment->specialist ? ucfirst(str_replace('daria', '', $appointment->specialist->sub_brand)) : 'N/A',
                    $appointment->service ? $appointment->service->name : 'N/A',
                    $this->getStatusLabel($appointment->status),
                    $appointment->service ? $appointment->service->price : '0',
                    $appointment->service ? $appointment->service->duration : '0',
                    $appointment->created_at->format('d.m.Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'În așteptare',
            'confirmed' => 'Confirmată',
            'completed' => 'Finalizată',
            'cancelled' => 'Anulată'
        ];
        
        return $labels[$status] ?? $status;
    }
}
