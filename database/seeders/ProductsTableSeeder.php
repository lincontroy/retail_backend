<?php
// database/seeders/ProductsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'barcode' => 6166000000001,
                'supplier_reference' => 1,
                'english_description' => "Bel's Organic Garlic 250g",
                'brand' => "Bel's Organic",
                'increment' => 12,
                'pcb' => 12
            ],
            [
                'barcode' => 6166000000002,
                'supplier_reference' => 2,
                'english_description' => "Bel's Organic Garlic 350g",
                'brand' => "Bel's Organic",
                'increment' => 12,
                'pcb' => 12
            ],
            // Add all other products from your table
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}