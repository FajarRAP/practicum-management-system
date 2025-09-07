<?php

namespace App\Http\Controllers;

// use App\Models\Course;
// use App\Models\Day;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// class ScheduleController extends Controller
// {
//     public function index(Request $request)
//     {
//         $perPage = $request->query('per_page', 10);

//         return view('assistants.schedule', [
//             'schedules' => Schedule::paginate($perPage)
//                 ->appends(['per_page' => $perPage]),
//             'courses' => Course::all(),
//             'days' => Day::all(),
//         ]);
//     }

//     public function show(Request $request, Schedule $schedule)
//     {
//         return view('assistants.schedule-show', [
//             'schedule' => $schedule,
//         ]);
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validateWithBag('addSchedule', [
//             'course' => ['required', 'exists:courses,id'],
//             'day' => ['required', 'exists:days,id'],
//             'shift' => ['nullable'],
//             'time' => ['required', 'date_format:H:i'],
//             'academic_year' => ['required', 'string', 'max:255'],
//         ]);

//         Schedule::create([
//             'course_id' => $validated['course'],
//             'day_id' => $validated['day'],
//             'shift' => $validated['shift'],
//             'time' => $validated['time'],
//             'academic_year' => $validated['academic_year'],
//         ]);

//         return back()->with('success', 'Schedule created successfully.');
//     }
// }
class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addSchedule', [
            'practicum_id' => ['required', 'exists:practicums,id'],
            'meeting_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('schedules')->where('practicum_id', $request->practicum_id)
            ],
            'topic' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s,H:i'],
            'end_time' => ['required', 'date_format:H:i:s,H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        Schedule::create($validated);

        return back()->with('success', 'Schedule added successfully.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validateWithBag('updateSchedule', [
            'meeting_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('schedules')->where('practicum_id', $schedule->practicum_id)->ignore($schedule->id)
            ],
            'topic' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s,H:i'],
            'end_time' => ['required', 'date_format:H:i:s,H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $schedule->update($validated);

        return back()->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();
            return back()->with('success', 'Schedule deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to delete schedule. It may have related data that prevents deletion.');
        }
    }
}
