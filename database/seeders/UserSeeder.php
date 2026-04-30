<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['role_name' => 'Admin'],
            ['description' => 'Full system access']
        );

        User::firstOrCreate(
            ['email' => 'admin@preciousPro.com'],
            [
                'full_name' => 'System Administrator',
                'password'  => Hash::make('Admin@12345'), // Change immediately after first login
                'role_id'   => $adminRole->id,
            ]
        );
    }
}
