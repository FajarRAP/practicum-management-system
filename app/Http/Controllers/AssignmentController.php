<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {

        $perPage = $request->query('per_page', 10);
        $assignments = Assignment::query();
        $announcements = Announcement::query();
        $assistantDatas = [
            'announcements' => $announcements->where(['is_schedule_announcement' => true, 'is_approved' => true])->get(),
            'assignments' => $assignments->paginate($perPage)->appends(['per_page' => $perPage]),
        ];

        $studentDatas = [
            'assignments' => $assignments
                ->join('announcements', 'assignments.announcement_id', '=', 'announcements.id')
                ->join('enrollments', 'announcements.schedule_id', '=', 'enrollments.schedule_id')
                ->select('assignments.*', 'enrollments.user_id')
                ->where('enrollments.user_id', $request->user()->id)
                ->paginate($perPage)
                ->appends(['per_page' => $perPage]),
        ];

        return $request->user()->hasRole('student') ?
            view('students.assignment', $studentDatas) :
            view('assistants.assignment', $assistantDatas);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addAssignment', [
            'announcement' => ['required', 'exists:announcements,id'],
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
        ]);

        Assignment::create([
            'announcement_id' => $validated['announcement'],
            'title' => $validated['title'],
            'due_date' => $validated['due_date'],
        ]);

        return back()->with('success', 'Assignment created successfully.');
    }
}
