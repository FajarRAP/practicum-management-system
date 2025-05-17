<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        return  view('assistants.attendance', [
            'schedules' => Schedule::paginate($perPage)->appends(['per_page' => $perPage]),
        ]);
    }
    public function show(Request $request, Schedule $schedule)
    {
        return view('assistants.attendance-list');
    }
}
