<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteShop extends Model
{
    //
    protected $table = 'route_shop';
    protected $fillable = [
        'route_id',
        'shop_id',
        'order',
        'estimated_arrival',
        'estimated_departure',
        'duration_minutes',
        'notes'
    ];
    public $timestamps = true;

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    
}
