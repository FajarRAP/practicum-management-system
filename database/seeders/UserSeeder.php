<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Fajar',
                'email' => 'fajar@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Assistant',
                'email' => 'assistant@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Lecturer',
                'email' => 'lecturer@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Lab Tech',
                'email' => 'labtech@gmail.com',
                'password' => bcrypt('password'),
            ],
        ];

        $roles = [
            'student',
            'assistant',
            'lecturer',
            'lab_tech',
        ];

        foreach ($users as $i => $user) {
            User::create($user)->assignRole($roles[$i]);
        }
    }
}
