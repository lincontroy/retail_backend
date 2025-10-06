<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Route;
use App\Models\Day;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email_or_mobile' => 'required|string',
            'password' => 'required|string'
        ]);
    
        $user = User::where('email', $data['email_or_mobile'])
                    ->orWhere('mobile', $data['email_or_mobile'])
                    ->first();
    
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $token = $user->createToken('api-token')->plainTextToken;

         // DEBUG: Check user ID
    \Log::info("User logged in - ID: {$user->id}, Name: {$user->name}");

    // DEBUG: Check today's day
    $todayCode = strtolower(now()->englishDayOfWeek);
    \Log::info("Today's code: {$todayCode}");

    // DEBUG: Check if route exists
    $day = Day::where('code', $todayCode)->first();
    if ($day) {
        \Log::info("Day found - ID: {$day->id}, Name: {$day->name}");
        
        $route = Route::where('user_id', $user->id)
            ->where('day_id', $day->id)
            ->active()
            ->first();
            
        if ($route) {
            \Log::info("Route found - ID: {$route->id}, Name: {$route->name}");
            \Log::info("Shops count: " . $route->shops()->count());
        } else {
            \Log::info("NO ROUTE FOUND for user {$user->id} on day {$day->id}");
        }
    }
    
        // Load today's route with shops
        $user->load(['todayRoutes']);

        $todayRoutes = $user->todayRoutes->map(function($route) {
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
                        'latitude' => $shop->latitude,
                        'longitude' => $shop->longitude,
                        'order' => $shop->pivot->order,
                        'estimated_arrival' => $shop->pivot->estimated_arrival,
                        'chain' => $shop->chain ? $shop->chain->name : null
                    ];
                })
            ];
        });
    
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'has_routes_today' => $user->has_routes_today,
                'routes_count_today' => $todayRoutes->count()
            ],
            'today_routes' => $todayRoutes,
            'routes_summary' => [
                'total_routes' => $todayRoutes->count(),
                'total_shops' => $todayRoutes->sum('shop_count'),
                'active_route' => $todayRoutes->firstWhere('is_currently_active', true)
            ]
        ]);
    
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}
