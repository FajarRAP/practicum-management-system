<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            ['name' => 'Shift 1'],
            ['name' => 'Shift 2'],
        ];
        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
