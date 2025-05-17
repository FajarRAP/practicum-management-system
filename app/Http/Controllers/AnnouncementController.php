<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\Shift;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $courses = Course::all();
        $shifts = Shift::all();
        $announcements = Announcement::paginate($perPage)->appends(['per_page' => $perPage]);

        $assistantDatas = [
            'announcements' => $announcements,
            'courses' => $courses,
            'shifts' => $shifts,
        ];

        return $request->user()->hasRole('student') ? view('students.announcement') : view('assistants.announcement', $assistantDatas);
    }

    public function store(Request $request)
    {

        $validated = $request->validateWithBag('addAnnouncement', [
            'course' => ['required', 'exists:courses,id'],
            'shift' => ['required', 'exists:shifts,id'],
            'activity' => ['required', 'string', 'max:255'],
            'place' => ['required', 'string', 'max:255'],
            'datetime' => ['required', 'date'],
        ]);

        Announcement::create([
            'course_id' => $validated['course'],
            'shift_id' => $validated['shift'],
            'activity' => $validated['activity'],
            'place' => $validated['place'],
            'datetime' => $validated['datetime'],
        ]);

        return back()->with('success', 'Announcement created successfully.');
    }
}
