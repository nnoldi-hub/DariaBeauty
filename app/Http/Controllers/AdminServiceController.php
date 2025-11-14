<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;

class AdminServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of services
     */
    public function index(Request $request)
    {
        $query = Service::query()->with('specialist');

        // Filter by sub-brand
        if ($request->filled('sub_brand')) {
            $query->where('sub_brand', $request->sub_brand);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $services = $query->latest()->paginate(15);
        
        // Get all categories for filter
        $categories = Service::select('category')->distinct()->pluck('category');

        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        $specialists = User::where('role', 'specialist')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.services.create', compact('specialists'));
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'specialist_id' => 'required|exists:users,id',
            'sub_brand' => 'required|in:dariaNails,dariaHair,dariaGlow',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        Service::create($validated);

        return redirect()->route('admin.services-crud.index')
            ->with('success', 'Serviciul a fost adăugat cu succes!');
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(Service $service)
    {
        $specialists = User::where('role', 'specialist')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.services.edit', compact('service', 'specialists'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'specialist_id' => 'required|exists:users,id',
            'sub_brand' => 'required|in:dariaNails,dariaHair,dariaGlow',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:15',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service->update($validated);

        return redirect()->route('admin.services-crud.index')
            ->with('success', 'Serviciul a fost actualizat cu succes!');
    }

    /**
     * Remove the specified service
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services-crud.index')
            ->with('success', 'Serviciul a fost șters cu succes!');
    }

    /**
     * Toggle service active status
     */
    public function toggleStatus(Service $service)
    {
        $service->is_active = !$service->is_active;
        $service->save();

        return redirect()->back()
            ->with('success', 'Statusul serviciului a fost actualizat!');
    }
}
