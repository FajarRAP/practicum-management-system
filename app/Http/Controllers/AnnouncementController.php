<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Shift;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $schedules = Schedule::all();
        $announcements = Announcement::paginate($perPage)->appends(['per_page' => $perPage]);

        $assistantDatas = [
            'announcements' => $announcements,
            'schedules' => $schedules,
        ];

        return $request->user()->hasRole('student') ? view('students.announcement') : view('assistants.announcement', $assistantDatas);
    }

    public function store(Request $request)
    {

        $validated = $request->validateWithBag('addAnnouncement', [
            'schedule' => ['required', 'exists:schedules,id'],
            'datetime' => ['required', 'date'],
            'activity' => ['required', 'string', 'max:255'],
            'place' => ['required', 'string', 'max:255'],
        ]);

        Announcement::create([
            'schedule_id' => $validated['schedule'],
            'activity' => $validated['activity'],
            'place' => $validated['place'],
            'datetime' => $validated['datetime'],
        ]);

        return back()->with('success', 'Announcement created successfully.');
    }
}
