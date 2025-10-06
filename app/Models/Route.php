<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day_id',
        'name',
        'notes',
        'description',
        'start_time', // NEW
        'end_time',   // NEW
        'area',       // NEW
        'priority',   // NEW
        'estimated_duration',
        'estimated_distance',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'estimated_duration' => 'integer',
        'estimated_distance' => 'decimal:2',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'priority' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'route_shop')
                    ->withPivot([
                        'order', 
                        'estimated_arrival', 
                        'estimated_departure', 
                        'duration_minutes', 
                        'notes'
                    ])
                    ->orderBy('route_shop.order')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDay($query, $dayId)
    {
        return $query->where('day_id', $dayId);
    }

    public function scopeByDayCode($query, $dayCode)
    {
        return $query->whereHas('day', function($q) use ($dayCode) {
            $q->where('code', $dayCode);
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority')
                    ->orderBy('start_time');
    }

    public function scopeWithDay($query)
    {
        return $query->with('day');
    }

    public function scopeWithShops($query)
    {
        return $query->with(['shops.chain', 'shops' => function($query) {
            $query->orderBy('route_shop.order');
        }]);
    }

    // Additional methods
    public function getTotalShopsAttribute()
    {
        return $this->shops_count ?? $this->shops()->count();
    }

    public function getFormattedTimeRangeAttribute()
    {
        if (!$this->start_time) return null;
        
        $start = \Carbon\Carbon::parse($this->start_time)->format('H:i');
        $end = $this->end_time ? \Carbon\Carbon::parse($this->end_time)->format('H:i') : null;
        
        return $end ? "{$start} - {$end}" : "Starts at {$start}";
    }

    public function isCurrentlyActive()
    {
        if (!$this->start_time || !$this->end_time) return false;
        
        $now = now();
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        return $now->between($start, $end);
    }
}