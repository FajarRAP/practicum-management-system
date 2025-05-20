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

        return view('assistants.assessment', [
            'announcements' => Announcement::where('is_schedule_announcement', true)
                ->paginate($perPage)
                ->appends(['per_page' => $perPage]),
        ]);
    }

    public function show(Announcement $announcement)
    {

        return view('assistants.assessment-show', [
            'announcement' => $announcement,
            'submissions' => $announcement
                ->join('enrollments', 'enrollments.schedule_id', '=', 'announcements.schedule_id')
                ->join('users', 'users.id', '=', 'enrollments.user_id')
                ->join('attendances', 'attendances.user_id', '=', 'enrollments.user_id')
                ->join('assignments', 'assignments.announcement_id', '=', 'announcements.id')
                ->join('assignment_submissions', 'assignment_submissions.user_id', '=', 'attendances.user_id')
                ->select('users.id as user_id', 'users.name', 'users.email', 'attendances.status', 'assignment_submissions.file_path')
                ->get(),
        ]);
    }

    public function store(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'assessments.*.participation' => 'nullable|numeric|min:0|max:100',
            'assessments.*.activeness' => 'nullable|numeric|min:0|max:100',
            'assessments.*.report' => 'nullable|numeric|min:0|max:100',
        ]);

        $mappedAssessments = [];
        foreach ($validated['assessments'] as $userId => $score) {
            $mappedAssessments[] = [
                'announcement_id' => $announcement->id,
                'user_id' => $userId,
                'participation' => $score['participation'],
                'activeness' => $score['activeness'],
                'report' => $score['report'],
            ];
        }

        Assessment::upsert(
            $mappedAssessments,
            ['announcement_id', 'user_id'],
            ['participation_score', 'active_score', 'report_score']
        );

        return back()->with('success', 'Assessment created successfully.');
    }
}
