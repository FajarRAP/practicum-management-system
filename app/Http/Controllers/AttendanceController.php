<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Practicum;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request, Practicum $practicum, Schedule $schedule)
    {
        // Do Gate Authorization here

        $schedule->load([
            // If status column has added
            // 'practicum.enrollments' => function (Builder $query) {
            //     return $query->where('status', 'APPROVED');
            // },
            'practicum.enrollments',
            'practicum.enrollments.user',
            'practicum.course',
            'attendances',
        ]);

        $attendances = $schedule->attendances->keyBy('user_id');
        

        return view('attendance.manage', [
            'attendances' => $attendances,
            'practicum' => $practicum,
            'schedule' => $schedule,
        ]);
    }

    public function store(Request $request)
    {
        // Do Gate Authorization here

        $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'attendances' => ['required', 'array'],
            'attendances.*' => ['required', 'in:PRESENT,SICK,EXCUSED,ABSENT'],
        ]);

        $scheduleId = $request->schedule_id;
        $attendancesData = $request->attendances;

        foreach ($attendancesData as $userId => $status) {
            Attendance::updateOrCreate(
                [
                    'schedule_id' => $scheduleId,
                    'user_id' => $userId,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return back()->with('success', 'Attendance has been successfully saved.');
    }
    // public function index(Request $request)
    // {
    //     $perPage = $request->query('per_page', 10);

    //     return  view('assistants.attendance', [
    //         'announcements' => Announcement::where('is_approved', true)
    //             ->paginate($perPage)
    //             ->appends(['per_page' => $perPage]),
    //     ]);
    // }

    // public function show(Announcement $announcement)
    // {
    //     return view('assistants.attendance-show', [
    //         'assistants' => User::role('assistant')->get(),
    //         'announcement' => $announcement,
    //     ]);
    // }

    // public function store(Request $request, Announcement $announcement)
    // {
    //     $users = $request->input('users', []);

    //     $mappedUsers = [];
    //     foreach ($users as $userId => $status) {
    //         $mappedUsers[] = [
    //             'announcement_id' => $announcement->id,
    //             'user_id' => $userId,
    //             'status' => $status,
    //         ];
    //     }

    //     Attendance::upsert(
    //         $mappedUsers,
    //         ['announcement_id', 'user_id'],
    //         ['status']
    //     );

    //     return back()->with('success', 'Attendance marked successfully.');
    // }
}
