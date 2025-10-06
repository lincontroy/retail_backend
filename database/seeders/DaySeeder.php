<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaySeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            ['name' => 'MONDAY', 'code' => 'mon', 'order' => 1],
            ['name' => 'TUESDAY', 'code' => 'tue', 'order' => 2],
            ['name' => 'WEDNESDAY', 'code' => 'wed', 'order' => 3],
            ['name' => 'THURSDAY', 'code' => 'thu', 'order' => 4],
            ['name' => 'FRIDAY', 'code' => 'fri', 'order' => 5],
            ['name' => 'SATURDAY', 'code' => 'sat', 'order' => 6],
            ['name' => 'SUNDAY', 'code' => 'sun', 'order' => 7],
        ];

        foreach ($days as $day) {
            DB::table('days')->updateOrInsert(
                ['code' => $day['code']],
                $day
            );
        }
    }
}