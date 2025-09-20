<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Practicum;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
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
            'practicum.enrollments',
            'practicum.enrollments.user',
            'practicum.course',
            'attendances',
            'assignment.submissions'
        ]);

        $attendances = $schedule->attendances->keyBy('user_id');

        $submissions = collect();
        if ($schedule->assignment) {
            $submissions = $schedule->assignment->submissions->keyBy('user_id');
        }

        $enrollments = $schedule->practicum->enrollments;

        return view('attendance.manage', [
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
            'attendances' => ['required', 'array'],
            'attendances.*' => ['required', 'in:PRESENT,SICK,EXCUSED,ABSENT'],
            'scores' => ['nullable', 'array'],
            'scores.*.participation_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.creativity_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.report_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.active_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.module_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $scheduleId = $validated['schedule_id'];
        $attendancesData = $validated['attendances'];
        $scoresData = $validated['scores'] ?? [];

        foreach ($attendancesData as $userId => $status) {
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

        return back()->with('success', 'Meeting records have been successfully saved.');
    }
}
