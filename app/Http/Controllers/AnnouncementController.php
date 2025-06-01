<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $schedules = Schedule::all();
        $announcements = Announcement::query();

        $assistantDatas = [
            'announcements' => $announcements
                ->paginate($perPage)
                ->appends(['per_page' => $perPage]),
            'schedules' => $schedules,
        ];

        $studentDatas = [
            'announcements' => $announcements
                ->where('announcements.is_approved', '=', 1)
                ->join('enrollments', 'announcements.schedule_id', '=', 'enrollments.schedule_id')
                ->where('enrollments.user_id', '=', $request->user()->id)
                ->paginate($perPage)
                ->appends(['per_page' => $perPage]),
        ];

        return view(
            'announcement',
            $request->user()->hasRole('student') ? $studentDatas : $assistantDatas
        );
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
            'is_schedule_announcement' => $request->boolean('is_schedule_announcement'),
        ]);

        return back()->with('success', 'Announcement created successfully.');
    }

    public function confirmAnnouncement(Request $request, Announcement $announcement)
    {
        if (!$request->user()->hasRole('lab_tech')) {
            return back()->with('error', 'You do not have permission to confirm announcements.');
        }

        $announcement->is_approved = $request->boolean('is_approved');
        $announcement->save();

        return back()->with('success', 'Confirm Announcement Successful');
    }
}
