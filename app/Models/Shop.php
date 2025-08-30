<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shop extends Model
{
    protected $fillable = ['name','code','address','latitude','longitude','route_id'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(RouteModel::class, 'route_id');
    }
}
