<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['student', 'assistant', 'lecturer', 'lab_tech', 'practicum_chief', 'lab_chief'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
