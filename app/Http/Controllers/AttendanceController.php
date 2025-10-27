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
            'course_id' => ['required', 'exists:courses,id'],
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
            $userScores = $validated['scores'][$userId] ?? [];
            $attendanceScore = $userScores['attendance_score'] ?? 0;
            $participationScore = $userScores['participation_score'] ?? 0;
            $creativityScore = $userScores['creativity_score'] ?? 0;
            $quizScore = $userScores['quiz_score'] ?? 0;
            $reportScore = $userScores['report_score'] ?? 0;
            $activeScore = $validated['course_id'] == 1 ? ($attendanceScore + $participationScore + $creativityScore) / 3 : 0;
            $moduleScore = $validated['course_id'] == 1 ?
                $activeScore * .35 + $reportScore * .65 // Pemrograman
                : ($attendanceScore + $participationScore + $quizScore) / 3; // APSI

            Attendance::updateOrCreate(
                [
                    'schedule_id' => $scheduleId,
                    'user_id' => $userId,
                ],
                [
                    'status' => $status,
                    'attendance_score'    => $attendanceScore,
                    'participation_score' => $participationScore,
                    'creativity_score'    => $creativityScore,
                    'quiz_score'          => $quizScore,
                    'report_score'        => $reportScore,
                    'active_score'        => $activeScore,
                    'module_score'        => $moduleScore,
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
