<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChainSeeder extends Seeder
{
    public function run(): void
    {
        $chains = [
            ['name' => 'Naivas', 'code' => 'naivas', 'color' => '#FF6B6B'],
            ['name' => 'Carrefour', 'code' => 'carrefour', 'color' => '#4ECDC4'],
            ['name' => 'Quickmart', 'code' => 'quickmart', 'color' => '#45B7D1'],
            ['name' => 'Chandarana', 'code' => 'chandarana', 'color' => '#96CEB4'],
            ['name' => 'Zucchini', 'code' => 'zucchini', 'color' => '#FFEAA7'],
            ['name' => 'Cleanshelf', 'code' => 'cleanshelf', 'color' => '#DDA0DD'],
            ['name' => '99 Mart', 'code' => '99mart', 'color' => '#98D8C8'],
        ];

        foreach ($chains as $chain) {
            DB::table('chains')->updateOrInsert(
                ['code' => $chain['code']],
                $chain
            );
        }
    }
}