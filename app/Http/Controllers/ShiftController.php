<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Day;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        return view('assistants.shift', [
            'shifts' => Shift::paginate($perPage)
                ->appends(['per_page' => $perPage]),
            'courses' => Course::all(),
            'days' => Day::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course' => ['required', 'exists:courses,id'],
            'day' => ['required', 'exists:days,id'],
            'shift' => ['required'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        Shift::create([
            'course_id' => $validated['course'],
            'day_id' => $validated['day'],
            'shift' => $validated['shift'],
            'time' => $validated['time'],
        ]);

        return back()->with('success', 'Shift created successfully.');
    }
}
