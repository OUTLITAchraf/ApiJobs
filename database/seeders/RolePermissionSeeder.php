<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $employer = Role::create(['name' => 'employer']);
        $user = Role::create(['name' => 'user']);

        // Create permissions for offres
        Permission::create(['name' => 'create offre']);
        Permission::create(['name' => 'edit offre']);
        Permission::create(['name' => 'delete offre']);
        Permission::create(['name' => 'view offre']);

        // Assign permissions
        $employer->givePermissionTo(['create offre', 'edit offre', 'delete offre', 'view offre']);
        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo(['view offre']);
    }
}

