<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $guarded = [];

    protected $dates = ['checked_in_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    public function shopProducts()
{
    return $this->hasMany(ShopProduct::class, 'shop_id', 'shop_id');
}
}
