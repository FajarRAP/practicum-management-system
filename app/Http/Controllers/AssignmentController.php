<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {

        $perPage = $request->query('per_page', 10);
        $assignments = Assignment::query();
        $schedules = Schedule::all();

        $assistantDatas = [
            'schedules' => $schedules,
            'assignments' => $assignments->paginate($perPage)->appends(['per_page' => $perPage]),
        ];

        $studentDatas = [
            'assignments' => $assignments->join('enrollments', 'assignments.schedule_id', '=', 'enrollments.schedule_id')
                ->select('assignments.*', 'enrollments.user_id')
                ->where('enrollments.user_id', $request->user()->id)
                ->paginate($perPage)->appends(['per_page' => $perPage]),
        ];

        return $request->user()->hasRole('student') ?
            view('students.assignment', $studentDatas) :
            view('assistants.assignment', $assistantDatas);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addAssignment', [
            'schedule' => 'required|exists:schedules,id',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        Assignment::create([
            'schedule_id' => $validated['schedule'],
            'title' => $validated['title'],
            'due_date' => $validated['due_date'],
        ]);

        return back()->with('success', 'Assignment created successfully.');
    }
}
