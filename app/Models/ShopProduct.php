<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ShopProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'product_id',
        'quantity',
        'notes',
        'images'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    // Relationships
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor for full image URLs
    public function getImageUrlsAttribute()
    {
        if (!$this->images || empty($this->images)) {
            return [];
        }

        return array_map(function ($image) {
            return $image ? asset('storage/' . $image) : null;
        }, $this->images);
    }

    // Add this method to debug images
    public function getImagesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded ?: [];
        }

        return $value ?: [];
    }

    // Mutator to ensure images are properly stored as JSON
    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['images'] = json_encode(array_values($value)); // Ensure it's a JSON array
        } else {
            $this->attributes['images'] = json_encode([]);
        }
    }
}