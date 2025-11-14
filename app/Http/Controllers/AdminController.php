<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function services()
    {
        return view('admin.services');
    }

    public function appointments()
    {
        return view('admin.appointments');
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        // Logica pentru actualizarea setărilor
        return redirect()->back()->with('success', 'Setările au fost actualizate.');
    }

    // Lista specialistilor in asteptare de aprobare
    public function pendingSpecialists()
    {
        $pending = User::where('role','specialist')->where('is_active', false)->latest()->paginate(20);
        return view('admin.pending-specialists', compact('pending'));
    }

    // Aprobare specialist
    public function approveSpecialist($id)
    {
        $user = User::where('role','specialist')->findOrFail($id);
        $user->is_active = true;
        $user->save();

        return redirect()->back()->with('success', 'Specialistul a fost aprobat.');
    }

    // Respingere specialist
    public function rejectSpecialist($id)
    {
        $user = User::where('role','specialist')->findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Cererea a fost respinsă și contul a fost șters.');
    }
}