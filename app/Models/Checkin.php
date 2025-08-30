<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $fillable = ['shop_id','user_id','latitude','longitude','device_info','checked_in_at'];

    protected $dates = ['checked_in_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
