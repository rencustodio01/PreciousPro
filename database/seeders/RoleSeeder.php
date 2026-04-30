<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'Admin',               'description' => 'Full system access'],
            ['role_name' => 'Production Manager',  'description' => 'Manages production runs and products'],
            ['role_name' => 'QC Officer',           'description' => 'Performs quality inspections'],
            ['role_name' => 'Inventory Officer',    'description' => 'Manages stock and inventory'],
            ['role_name' => 'Finance Officer',      'description' => 'Records production costs and finance data'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['role_name' => $role['role_name']], $role);
        }
    }
}
