<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->hasRole('lab_tech')) {
            return redirect()->route('dashboard');
        }

        $perPage = $request->query('per_page', 10);

        $studentAnnouncements = collect([]);

        foreach ($request->user()->enrollments as $enrollment) {
            $studentAnnouncements->push(...$enrollment->schedule->announcements);
        }

        return $request->user()->hasRole('student') ?
            view('students.assessment', [
                'announcements' => $studentAnnouncements,
            ]) :
            view('assistants.assessment', [
                'announcements' => Announcement::paginate($perPage)
                    ->appends(['per_page' => $perPage])
            ]);
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
                ->leftJoin('assignments', 'assignments.announcement_id', '=', 'announcements.id')
                ->leftJoin('assignment_submissions', 'assignment_submissions.user_id', '=', 'attendances.user_id');
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
                'assessments.report_score',
                'assessments.active_score',
                'assessments.module_score'
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
            $activeScore = ($score['attendance'] + $score['participation'] + $score['creativity']) / 3;
            $moduleScore = $score['report'] ?? null ? $activeScore * .35 + $score['report'] * .65 : $activeScore * .35;

            $mappedAssessments[] = [
                'announcement_id' => $announcement->id,
                'user_id' => $userId,
                'attendance_score' => $score['attendance'] ?? null,
                'participation_score' => $score['participation'] ?? null,
                'creativity_score' => $score['creativity'] ?? null,
                'report_score' => $score['report'] ?? null,
                'active_score' => $activeScore ?? null,
                'module_score' => $moduleScore ?? null,
            ];
        }

        Assessment::upsert(
            $mappedAssessments,
            ['announcement_id', 'user_id'],
            ['attendance_score', 'participation_score', 'creativity_score', 'report_score', 'active_score', 'module_score']
        );

        return back()->with('success', 'Assessment saved successfully.');
    }

    public function finalScore(Request $request)
    {
        $assessments = collect([]);
        $courses = Course::all();
        $firstCourse = $courses->first();
        $lastCourse = $courses->last();

        // Pemrograman
        foreach ($firstCourse->schedules as $schedule) {
            foreach ($schedule->enrollments as $enrollment) {
                $data = collect([]);
                $activeScore = 0;
                $reportScore = 0;
                foreach ($enrollment->user->assessments as $assessment) {
                    $activeScore += $assessment->active_score ?? 0;
                    $reportScore += $assessment->report_score ?? 0;
                }
                $activeScore = ($activeScore / 9) * .35; // 1 Briefing + 8 Praktikum
                $reportScore = ($reportScore / 8) * .65; // 8 Praktikum
                $data->put('course', $firstCourse->name)
                    ->put('user', $enrollment->user)
                    ->put('active_score', $activeScore)
                    ->put('report_score', $reportScore)
                    ->put('final_score', $activeScore + $reportScore);
                $assessments->push($data);
            }
        }

        // Analisis Perancangan Sistem Informasi
        foreach ($lastCourse->schedules as $schedule) {
            foreach ($schedule->enrollments as $enrollment) {
                $data = collect([]);
                $activeScore = 0;
                $reportScore = 0;
                foreach ($enrollment->user->assessments as $assessment) {
                    $activeScore += $assessment->active_score ?? 0;
                    $reportScore += $assessment->report_score ?? 0;
                }
                $activeScore = ($activeScore / 7) * .35; // 1 Briefing + 5 Praktikum + 1 Presentasi
                $reportScore = ($reportScore / 8) * .65; // 8 Praktikum
                $data->put('course', $lastCourse->name)
                    ->put('user', $enrollment->user)
                    ->put('active_score', $activeScore)
                    ->put('report_score', $reportScore)
                    ->put('final_score', $activeScore + $reportScore);
                $assessments->push($data);
            }
        }

        $courses = $request->user()->hasRole('student') ? $assessments
            ->where(fn($item) => $item['user']['id'] == $request->user()->id) : $assessments;

        return view('final-score', [
            'courses' => $courses->groupBy('course'),
        ]);
    }
}
