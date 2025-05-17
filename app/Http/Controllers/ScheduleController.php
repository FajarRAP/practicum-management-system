<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Day;
use App\Models\Schedule;
use App\Models\Shift;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        return view('assistants.schedule', [
            'schedules' => Schedule::paginate($perPage)
                ->appends(['per_page' => $perPage]),
            'courses' => Course::all(),
            'days' => Day::all(),
            'shifts' => Shift::all(),
        ]);
    }

    public function show(Request $request, Schedule $schedule)
    {
        return view('assistants.schedule-show', [
            'schedule' => $schedule,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addSchedule', [
            'course' => ['required', 'exists:courses,id'],
            'day' => ['required', 'exists:days,id'],
            'shift' => ['required', 'exists:shifts,id'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        Schedule::create([
            'course_id' => $validated['course'],
            'day_id' => $validated['day'],
            'shift_id' => $validated['shift'],
            'time' => $validated['time'],
        ]);

        return back()->with('success', 'Shift created successfully.');
    }
}
