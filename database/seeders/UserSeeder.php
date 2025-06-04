<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSecurityQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assistants = [
            [
                'name' => 'Assistant',
                'email' => 'assistant@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Raisha',
                'email' => 'raisha.asistenssi@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Mutiah',
                'email' => 'mutiah.asistenssi@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Bene',
                'email' => 'bene.asistenssi@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Kalila',
                'email' => 'kalila.asistenssi@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Rama',
                'email' => 'rama.asistenssi@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Teguh',
                'email' => 'teguh.asistenssi@gmail.com',
                'password' => bcrypt('password'),
            ],
        ];

        $students = [
            [
                'name' => 'Fajar',
                'email' => 'fajar@gmail.com',
                'password' => bcrypt('password'),
            ],
        ];

        $lecturers = [
            [
                'name' => 'Lecturer',
                'email' => 'lecturer@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Dosen',
                'email' => 'dosen@gmail.com',
                'password' => bcrypt('password'),
            ],
        ];

        $labtechs = [
            [
                'name' => 'Lab Tech',
                'email' => 'labtech@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Laboran',
                'email' => 'laboranssi@gmail.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($students as $student) {
            $user = User::create($student)->assignRole('student');
            UserSecurityQuestion::create([
                'user_id' => $user->id,
                'security_question_id' => 1,
                'answer' => 'Jawaban',
            ]);
        }

        foreach ($assistants as $assistant) {
            $user = User::create($assistant)->assignRole('assistant');
            UserSecurityQuestion::create([
                'user_id' => $user->id,
                'security_question_id' => 1,
                'answer' => 'Jawaban',
            ]);
        }

        foreach ($lecturers as $lecturer) {
            $user = User::create($lecturer)->assignRole('lecturer');
            UserSecurityQuestion::create([
                'user_id' => $user->id,
                'security_question_id' => 1,
                'answer' => 'Jawaban',
            ]);
        }

        foreach ($labtechs as $labtech) {
            $user = User::create($labtech)->assignRole('lab_tech');
            UserSecurityQuestion::create([
                'user_id' => $user->id,
                'security_question_id' => 1,
                'answer' => 'Jawaban',
            ]);
        }
    }
}
