<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RouteModel extends Model
{
    protected $table = 'routes';
    protected $fillable = ['name','notes'];

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class, 'route_id');
    }

   
}
