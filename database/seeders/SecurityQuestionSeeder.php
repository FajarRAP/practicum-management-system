<?php

namespace Database\Seeders;

use App\Models\SecurityQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SecurityQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SecurityQuestion::create([
            'question' => 'Apa nama sekolah dasar tempat kamu pertama kali bersekolah?',
        ]);
    }
}
