<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Route;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2', // Add this if you have balance field
            'biometrics_enabled' => 'boolean', // Add this if you have the field
            'notifications_enabled' => 'boolean', // Add this if you have the field
        ];
    }

    // Relationship with routes
    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    // Today's route relationship - FIXED: added return statement
    public function todayRoutes()
{
    $todayCode = strtolower(now()->englishDayOfWeek);
    
    return $this->hasMany(Route::class)
                ->whereHas('day', function($query) use ($todayCode) {
                    $query->where('code', $todayCode);
                })
                ->active()
                ->ordered()
                ->with(['shops.chain', 'shops' => function($query) {
                    $query->orderBy('route_shop.order');
                }]);
}


public function getTodayRoutesAttribute()
{
    return $this->todayRoutes()->get();
}

public function getHasRoutesTodayAttribute()
{
    return $this->todayRoutes()->exists();
}

    // Additional helpful methods
    public function getRoutesForDay($dayCode)
    {
        return $this->routes()
                    ->whereHas('day', function($query) use ($dayCode) {
                        $query->where('code', strtolower($dayCode));
                    })
                    ->active()
                    ->ordered()
                    ->with(['shops.chain', 'shops' => function($query) {
                        $query->orderBy('route_shop.order');
                    }])
                    ->get();
    }
    // Get all active routes with shops
    public function getAllRoutesWithShops()
    {
        return $this->routes()
                    ->active()
                    ->with(['day', 'shops.chain', 'shops' => function($query) {
                        $query->orderBy('route_shop.order');
                    }])
                    ->get();
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_user')
                    ->withTimestamps();
    }
}