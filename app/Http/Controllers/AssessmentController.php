<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $announcements = Announcement::query();

        $assistantDatas = [
            'announcements' => $announcements
                ->paginate($perPage)
                ->appends(['per_page' => $perPage])
        ];

        $studentDatas = [
            'announcements' => $announcements
                ->join('enrollments', 'enrollments.schedule_id', '=', 'announcements.schedule_id')
                ->join('users', 'users.id', '=', 'enrollments.user_id')
                ->where('users.id', '=', $request->user()->id)
                ->where('is_schedule_announcement', true)
                ->join('attendances', 'attendances.user_id', '=', 'users.id')
                ->join('assessments', 'assessments.user_id', '=', 'users.id')
                ->join('assignment_submissions', 'assignment_submissions.user_id', '=', 'users.id')
                ->select('announcements.*', 'attendances.status', 'assignment_submissions.file_path', 'assessments.participation_score', 'assessments.active_score', 'assessments.report_score')
                ->paginate($perPage)
                ->appends(['per_page' => $perPage]),
        ];

        return $request->user()->hasRole('student') ?
            view('students.assessment', $studentDatas) :
            view('assistants.assessment', $assistantDatas);
    }

    public function show(Request $request, Announcement $announcement)
    {
        $fields = [
            'users.id as user_id',
            'users.name',
            'users.email',
            'users.identity_number',
            'attendances.status',
        ];

        $query = Announcement::query()
            ->join('enrollments', 'enrollments.schedule_id', '=', 'announcements.schedule_id')
            ->where('announcements.id', '=', $announcement->id)
            ->join('users', 'users.id', '=', 'enrollments.user_id')
            ->join('attendances', 'attendances.user_id', '=', 'enrollments.user_id')
            ->where('attendances.announcement_id', '=', $announcement->id);

        if ($announcement->is_schedule_announcement) {
            $query = $query
                ->join('assignments', 'assignments.announcement_id', '=', 'announcements.id')
                ->join('assignment_submissions', 'assignment_submissions.user_id', '=', 'attendances.user_id');
            $fields[] = 'assignment_submissions.file_path';
        }

        if (Assessment::where('announcement_id', $announcement->id)->exists()) {
            $query = $query
                ->join('assessments', 'assessments.user_id', '=', 'attendances.user_id')
                ->where('assessments.announcement_id', '=', $announcement->id);
            $fields = array_merge($fields, [
                'assessments.attendance_score',
                'assessments.participation_score',
                'assessments.creativity_score',
                'assessments.report_score'
            ]);
        }

        return view('assistants.assessment-show', [
            'announcement' => $announcement,
            'submissions' => $query->select($fields)->get(),
        ]);
    }

    public function store(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'assessments.*.attendance' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assessments.*.participation' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assessments.*.creativity' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assessments.*.report' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $mappedAssessments = [];
        foreach ($validated['assessments'] as $userId => $score) {
            $mappedAssessments[] = [
                'announcement_id' => $announcement->id,
                'user_id' => $userId,
                'attendance_score' => $score['attendance'] ?? null,
                'participation_score' => $score['participation'] ?? null,
                'creativity_score' => $score['creativity'] ?? null,
                'report_score' => $score['report'] ?? null,
            ];
        }

        Assessment::upsert(
            $mappedAssessments,
            ['announcement_id', 'user_id'],
            ['attendance_score', 'participation_score', 'creativity_score', 'report_score']
        );

        return back()->with('success', 'Assessment created successfully.');
    }
}
