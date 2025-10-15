<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run() : void {
        // Create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole  = Role::create(['name' => 'User']);

        // Create users and assign roles (One-to-One)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Normal User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456'),
            'role_id' => $userRole->id,
        ]);

        $this->command->info('Roles and Users seeded successfully!');
    }
}
