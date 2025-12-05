<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;

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
        $settings = Setting::getAll();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'platform_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:50',
            'platform_commission' => 'required|numeric|min:0|max:100',
            'default_start_time' => 'required',
            'default_end_time' => 'required',
        ]);

        // Save all settings
        $settingsToUpdate = [
            'platform_name',
            'contact_email',
            'contact_phone',
            'platform_commission',
            'default_start_time',
            'default_end_time',
        ];

        foreach ($settingsToUpdate as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }

        // Handle checkboxes (boolean values)
        $checkboxes = ['notify_new_specialist', 'notify_new_booking', 'notify_negative_review'];
        foreach ($checkboxes as $key) {
            Setting::set($key, $request->has($key) ? '1' : '0');
        }

        // Clear cache
        Setting::clearCache();

        return redirect()->back()->with('success', 'Setările au fost actualizate cu succes!');
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