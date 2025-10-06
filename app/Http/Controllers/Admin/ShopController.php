<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::all();
        $shops = Shop::with(['routes' => function($query) {
            $query->withPivot('order', 'estimated_arrival', 'estimated_departure', 'duration_minutes');
        }, 'users'])->paginate(20);
        
        return view('admin.shops.index', compact('shops', 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routes = Route::active()->get();
        return view('admin.shops.create', compact('routes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'route_ids' => 'nullable|array',
            'route_ids.*' => 'exists:routes,id',
            'orders' => 'nullable|array',
            'orders.*' => 'integer|min:0',
            'estimated_arrivals' => 'nullable|array',
            'estimated_departures' => 'nullable|array',
            'duration_minutes' => 'nullable|array',
            'duration_minutes.*' => 'integer|min:1',
            'route_notes' => 'nullable|array',
        ]);

        DB::transaction(function () use ($validated, $request, &$shop) {
            $shop = Shop::create($validated);

            if ($request->has('route_ids')) {
                $routeData = [];
                foreach ($request->route_ids as $index => $routeId) {
                    $order = $request->orders[$index] ?? null;
                    
                    // Check for duplicate order in the same route
                    if ($order !== null) {
                        $existing = DB::table('route_shop')
                            ->where('route_id', $routeId)
                            ->where('order', $order)
                            ->exists();
                            
                        if ($existing) {
                            // Auto-assign next available order
                            $maxOrder = DB::table('route_shop')
                                ->where('route_id', $routeId)
                                ->max('order') ?? 0;
                            $order = $maxOrder + 1;
                        }
                    }

                    $routeData[$routeId] = [
                        'order' => $order,
                        'estimated_arrival' => $request->estimated_arrivals[$index] ?? null,
                        'estimated_departure' => $request->estimated_departures[$index] ?? null,
                        'duration_minutes' => $request->duration_minutes[$index] ?? null,
                        'notes' => $request->route_notes[$index] ?? null,
                    ];
                }
                $shop->routes()->attach($routeData);
            }
        });

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop created successfully.');
    }

    /**
     * Display the specified resource - THIS WAS MISSING
     */
    public function show(Shop $shop)
    {
        $shop->load(['routes' => function($query) {
            $query->withPivot('order', 'estimated_arrival', 'estimated_departure', 'duration_minutes', 'notes')
                  ->orderBy('route_shop.order');
        }, 'users']);
        
        return view('admin.shops.show', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        $routes = Route::active()->get();
        $shop->load(['routes' => function($query) {
            $query->withPivot('order', 'estimated_arrival', 'estimated_departure', 'duration_minutes', 'notes');
        }]);
        
        return view('admin.shops.edit', compact('shop', 'routes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'route_ids' => 'nullable|array',
            'route_ids.*' => 'exists:routes,id',
            'orders' => 'nullable|array',
            'orders.*' => 'integer|min:0',
            'estimated_arrivals' => 'nullable|array',
            'estimated_departures' => 'nullable|array',
            'duration_minutes' => 'nullable|array',
            'duration_minutes.*' => 'integer|min:1',
            'route_notes' => 'nullable|array',
        ]);

        DB::transaction(function () use ($shop, $validated, $request) {
            $shop->update($validated);

            if ($request->has('route_ids')) {
                $routeData = [];
                foreach ($request->route_ids as $index => $routeId) {
                    $order = $request->orders[$index] ?? null;
                    
                    // Check for duplicate order (excluding current shop's assignments)
                    if ($order !== null) {
                        $existing = DB::table('route_shop')
                            ->where('route_id', $routeId)
                            ->where('order', $order)
                            ->where('shop_id', '!=', $shop->id) // Exclude current shop
                            ->exists();
                            
                        if ($existing) {
                            // Auto-assign next available order
                            $maxOrder = DB::table('route_shop')
                                ->where('route_id', $routeId)
                                ->max('order') ?? 0;
                            $order = $maxOrder + 1;
                        }
                    }

                    $routeData[$routeId] = [
                        'order' => $order,
                        'estimated_arrival' => $request->estimated_arrivals[$index] ?? null,
                        'estimated_departure' => $request->estimated_departures[$index] ?? null,
                        'duration_minutes' => $request->duration_minutes[$index] ?? null,
                        'notes' => $request->route_notes[$index] ?? null,
                    ];
                }
                $shop->routes()->sync($routeData);
            } else {
                $shop->routes()->detach();
            }
        });

        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        try {
            $shop->routes()->detach();
            $shop->users()->detach();
            $shop->delete();

            return redirect()->route('admin.shops.index')
                ->with('success', 'Shop deleted successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.shops.index')
                ->with('error', 'Failed to delete shop: ' . $e->getMessage());
        }
    }

    /**
     * Show form to manage shop routes
     */
    public function manageRoutes(Shop $shop)
    {
        $routes = Route::active()->get();
        $shopRoutes = $shop->routes()->withPivot('order', 'estimated_arrival', 'estimated_departure', 'duration_minutes', 'notes')->get();
        
        return view('admin.shops.manage-routes', compact('shop', 'routes', 'shopRoutes'));
    }

    /**
     * Update shop routes
     */
    public function updateRoutes(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'routes' => 'required|array',
            'routes.*.route_id' => 'required|exists:routes,id',
            'routes.*.order' => 'nullable|integer|min:0',
            'routes.*.estimated_arrival' => 'nullable|date_format:H:i',
            'routes.*.estimated_departure' => 'nullable|date_format:H:i|after:routes.*.estimated_arrival',
            'routes.*.duration_minutes' => 'nullable|integer|min:1',
            'routes.*.notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($shop, $validated) {
            // First, detach all existing routes
            $shop->routes()->detach();

            $routeData = [];
            foreach ($validated['routes'] as $route) {
                $routeId = $route['route_id'];
                
                // Check if this order is already taken for this route
                $existingOrder = DB::table('route_shop')
                    ->where('route_id', $routeId)
                    ->where('order', $route['order'] ?? 0)
                    ->exists();

                if ($existingOrder && isset($route['order'])) {
                    // Find the next available order number for this route
                    $maxOrder = DB::table('route_shop')
                        ->where('route_id', $routeId)
                        ->max('order') ?? 0;
                    
                    $route['order'] = $maxOrder + 1;
                }

                $routeData[$routeId] = [
                    'order' => $route['order'] ?? null,
                    'estimated_arrival' => $route['estimated_arrival'] ?? null,
                    'estimated_departure' => $route['estimated_departure'] ?? null,
                    'duration_minutes' => $route['duration_minutes'] ?? null,
                    'notes' => $route['notes'] ?? null,
                ];
            }

            $shop->routes()->sync($routeData);
        });

        return redirect()->route('admin.shops.show', $shop)
            ->with('success', 'Shop routes updated successfully.');
    }

    public function manageUsers(Shop $shop)
    {
        $users = User::all();
        $shopUsers = $shop->users;
        
        return view('admin.shops.manage-users', compact('shop', 'users', 'shopUsers'));
    }

    public function updateUsers(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $shop->users()->sync($validated['user_ids']);

        return redirect()->route('admin.shops.show', $shop)
            ->with('success', 'Shop users updated successfully.');
    }

    public function shopUsers()
    {
        $shopUsers = DB::table('shop_user')
            ->join('shops', 'shop_user.shop_id', '=', 'shops.id')
            ->join('users', 'shop_user.user_id', '=', 'users.id')
            ->select(
                'shop_user.id as pivot_id',
                'shops.id as shop_id',
                'shops.name as shop_name',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'shop_user.created_at',
                'shop_user.updated_at'
            )
            ->orderBy('shops.name')
            ->orderBy('users.name')
            ->paginate(20);

        return view('admin.shops.shop-users-index', compact('shopUsers'));
    }
}