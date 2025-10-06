<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Models\Shop;
use App\Models\Route;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{

    public function getUserRoutes(Request $request)
    {
        $user = Auth::user();

        $routes = Route::with(['day', 'shops.chain', 'shops' => function($query) {
            $query->orderBy('route_shop.order');
        }])
        ->where('user_id', $user->id)
        ->active()
        ->ordered()
        ->get();

        return response()->json([
            'success' => true,
            'routes' => $routes,
            'total_routes' => $routes->count(),
        ]);
    }

    public function getTodayRoutes(Request $request)
    {
        $user = Auth::user();
        $todayCode = strtolower(now()->englishDayOfWeek);

        $routes = Route::with(['day', 'shops.chain', 'shops' => function($query) {
            $query->orderBy('route_shop.order');
        }])
        ->where('user_id', $user->id)
        ->whereHas('day', function($query) use ($todayCode) {
            $query->where('code', $todayCode);
        })
        ->active()
        ->ordered()
        ->get();

        $formattedRoutes = $routes->map(function($route) {
            return [
                'id' => $route->id,
                'name' => $route->name,
                'description' => $route->description,
                'start_time' => $route->start_time,
                'end_time' => $route->end_time,
                'area' => $route->area,
                'priority' => $route->priority,
                'estimated_duration' => $route->estimated_duration,
                'estimated_distance' => $route->estimated_distance,
                'formatted_time_range' => $route->formatted_time_range,
                'is_currently_active' => $route->isCurrentlyActive(),
                'shop_count' => $route->shops->count(),
                'shops' => $route->shops->map(function($shop) {
                    return [
                        'id' => $shop->id,
                        'name' => $shop->name,
                        'address' => $shop->address,
                        'location' => $shop->location,
                        'latitude' => $shop->latitude,
                        'longitude' => $shop->longitude,
                        'contact_phone' => $shop->contact_phone,
                        'contact_email' => $shop->contact_email,
                        'chain' => $shop->chain ? $shop->chain->name : null,
                        'chain_color' => $shop->chain ? $shop->chain->color : null,
                        'order' => $shop->pivot->order,
                        'estimated_arrival' => $shop->pivot->estimated_arrival,
                        'estimated_departure' => $shop->pivot->estimated_departure,
                        'duration_minutes' => $shop->pivot->duration_minutes,
                        'notes' => $shop->pivot->notes,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'routes' => $formattedRoutes,
            'summary' => [
                'total_routes' => $routes->count(),
                'total_shops' => $routes->sum('shops_count'),
                'last_updated' => now()->toISOString(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|integer|exists:shops,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
           
        ]);
    
        $user = Auth::user();
    
        $checkIn = CheckIn::create([
            'user_id' => $user->id,
            'shop_id' => $request->shop_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'device_info' => $request->device_info ?? null,
           
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'check_in_id' => $checkIn->id, // Add this line
            'check_in_time' => now(),
        ]);
    }
    public function index(Request $request)
    {
        $query = Checkin::with('user','shop')->orderBy('checked_in_at','desc');
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        return response()->json($query->paginate(20));
    }
}
