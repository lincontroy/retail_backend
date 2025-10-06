<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'chain_id',
        'name',
        'code',
        'address',
        'location',
        'latitude',
        'longitude',
        'contact_phone',
        'contact_email',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Relationship with chain
    public function chain()
    {
        return $this->belongsTo(Chain::class);
    }

    // Many-to-many relationship with routes
    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_shop')
                    ->withPivot([
                        'order', 
                        'estimated_arrival', 
                        'estimated_departure', 
                        'duration_minutes', 
                        'notes'
                    ])
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByChain($query, $chainId)
    {
        return $query->where('chain_id', $chainId);
    }

    public function scopeByChainCode($query, $chainCode)
    {
        return $query->whereHas('chain', function($q) use ($chainCode) {
            $q->where('code', $chainCode);
        });
    }

    public function scopeNearby($query, $latitude, $longitude, $radiusKm = 10)
    {
        return $query->select('*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$latitude, $longitude, $latitude]
            )
            ->havingRaw('distance < ?', [$radiusKm])
            ->orderBy('distance');
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('code', 'LIKE', "%{$searchTerm}%")
              ->orWhere('address', 'LIKE', "%{$searchTerm}%")
              ->orWhere('location', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('chain', function($chainQuery) use ($searchTerm) {
                  $chainQuery->where('name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }

    // Additional helpful methods
    public function getFormattedContactAttribute()
    {
        if ($this->contact_phone && $this->contact_email) {
            return "{$this->contact_phone} | {$this->contact_email}";
        }
        
        return $this->contact_phone ?: $this->contact_email;
    }

    public function getLocationCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "{$this->latitude},{$this->longitude}";
        }
        
        return null;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'shop_user')
                    ->withTimestamps();
    }

    // Check if shop has coordinates
    public function hasCoordinates()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }
}