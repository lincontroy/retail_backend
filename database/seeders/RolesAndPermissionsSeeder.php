<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache (important!)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Example permissions (optional)
        Permission::firstOrCreate(['name' => 'edit articles']);
        Permission::firstOrCreate(['name' => 'delete articles']);
        Permission::firstOrCreate(['name' => 'publish articles']);

        // Create Admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $retailerRole=Role::firstOrCreate(['name' => 'Retailer']);
        $adminRole->givePermissionTo(Permission::all());

        // Assign role to first user (id=1)
        $user = User::find(1);
        if ($user) {
            $user->assignRole('admin');
            $this->command->info("User {$user->email} assigned as admin ✅");
        }
    }
}
