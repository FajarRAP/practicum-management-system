<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Day;
use App\Models\Schedule;
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
            'shift' => ['nullable'],
            'time' => ['required', 'date_format:H:i'],
            'academic_year' => ['required', 'string', 'max:255'],
        ]);

        Schedule::create([
            'course_id' => $validated['course'],
            'day_id' => $validated['day'],
            'shift' => $validated['shift'],
            'time' => $validated['time'],
            'academic_year' => $validated['academic_year'],
        ]);

        return back()->with('success', 'Schedule created successfully.');
    }
}
