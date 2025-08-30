<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email'=>'admin@example.com'],
            [
                'name'=>'Administrator',
                'mobile'=>'254700000000',
                'password'=>Hash::make('password'),
                'is_admin'=>true
            ]
        );
    }
}
