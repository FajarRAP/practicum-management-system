<?php

namespace App\Http\Controllers;

use App\Models\AssistantAttendance;
use App\Models\Attendance;
use App\Models\Practicum;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request, Practicum $practicum, Schedule $schedule)
    {
        // Authorize here

        $schedule->load([
            // If status column has added
            // 'practicum.enrollments' => function (Builder $query) {
            //     return $query->where('status', 'APPROVED');
            // },
            'practicum.staff' => function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'assistant');
                });
            },
            'practicum.enrollments',
            'practicum.enrollments.user',
            'practicum.course',
            'attendances',
            'assignment.submissions',
            'assistantAttendances'
        ]);

        $practicum = $schedule->practicum;
        $enrollments = $practicum->enrollments;
        $assistants = $practicum->staff;

        $attendances = $schedule->attendances->keyBy('user_id');
        $assistantAttendances = $schedule->assistantAttendances->keyBy('user_id');

        $submissions = collect();
        if ($schedule->assignment) {
            $submissions = $schedule->assignment->submissions->keyBy('user_id');
        }

        return view('attendance.manage', [
            'assistants' => $assistants,
            'assistantAttendances' => $assistantAttendances,
            'attendances' => $attendances,
            'enrollments' => $enrollments,
            'practicum' => $practicum,
            'schedule' => $schedule,
            'submissions' => $submissions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            // student attendance
            'attendances' => ['required', 'array'],
            'attendances.*' => ['required', 'in:PRESENT,EXCUSED,SICK,ABSENT'],
            'scores' => ['nullable', 'array'],
            'scores.*.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
            // assistant attendance
            'assistant_attendances' => ['nullable', 'array'],
            'assistant_attendances.*' => ['required', 'in:PRESENT,EXCUSED,SICK,ABSENT'],
        ]);

        $scheduleId = $validated['schedule_id'];

        foreach ($validated['attendances'] as $userId => $status) {
            $userScores = $scoresData[$userId] ?? [];

            Attendance::updateOrCreate(
                [
                    'schedule_id' => $scheduleId,
                    'user_id' => $userId,
                ],
                [
                    'status' => $status,
                    'participation_score' => $userScores['participation_score'] ?? 0,
                    'creativity_score'    => $userScores['creativity_score'] ?? 0,
                    'report_score'        => $userScores['report_score'] ?? 0,
                    'active_score'        => $userScores['active_score'] ?? 0,
                    'module_score'        => $userScores['module_score'] ?? 0,
                ]
            );
        }

        if (isset($validated['assistant_attendances'])) {
            foreach ($validated['assistant_attendances'] as $assistantId => $status) {
                AssistantAttendance::updateOrCreate(
                    ['schedule_id' => $scheduleId, 'user_id' => $assistantId],
                    ['status' => $status]
                );
            }
        }

        return back()->with('success', 'All records have been successfully saved.');
    }
}
