<?php

namespace App\Http\Controllers\Admin;

use App\Models\Route;
use App\Models\Day;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::with(['user', 'day'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $users = User::all(); // Or User::where('is_active', true)->get() if you have active users
        $days = Day::all(); // Or however you get days
        return view('admin.routes.create', compact('users', 'days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'day_id' => 'nullable|exists:days,id',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'area' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:1|max:10',
            'estimated_duration' => 'nullable|integer|min:1',
            'estimated_distance' => 'nullable|string|max:100',
            'is_active' => 'boolean'
        ]);

        Route::create($validated);

        return redirect()->route('admin.routes.index')
            ->with('success', 'Route created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        $route->load(['user', 'day', 'shops']);
        return view('admin.routes.show', compact('route'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {

        $users = User::all(); // Or User::where('is_active', true)->get() if you have active users
        $days = Day::all(); // Or however you get days
        return view('admin.routes.edit', compact('route','users','days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'day_id' => 'nullable|exists:days,id',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'area' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:1|max:10',
            'estimated_duration' => 'nullable|integer|min:1',
            'estimated_distance' => 'nullable|string|max:100',
            'is_active' => 'boolean'
        ]);

        $route->update($validated);

        return redirect()->route('admin.routes.index')
            ->with('success', 'Route updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();

        return redirect()->route('admin.routes.index')
            ->with('success', 'Route deleted successfully.');
    }
}