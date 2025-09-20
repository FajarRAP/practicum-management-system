<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Practicum;
use App\Models\Shift;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PracticumController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Practicum::class);

        $perPage = $request->query('per_page', 10);

        $academicYears = AcademicYear::where('status', '!=', 'FINISHED')->orderBy('year', 'desc')->get();
        $courses = Course::orderBy('name')->get();
        $shifts = Shift::orderBy('name')->get();
        $practicums = Practicum::with(['course', 'academicYear', 'shift']);
        $user = $request->user();

        if (!$user->hasRole('lab_tech')) {
            $practicums->whereHas('staff', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return view('practicum.index', [
            'academicYears' => $academicYears,
            'courses' => $courses,
            'shifts' => $shifts,
            'practicums' => $practicums->latest()->paginate($perPage)->appends($request->query()),
        ]);
    }

    public function show(Request $request, Practicum $practicum)
    {
        Gate::authorize('view', $practicum);

        // Student
        if ($request->user()->hasRole('student')) {
            $practicum->load(['course', 'academicYear', 'shift', 'schedules', 'assignments']);

            $scheduleIds = $practicum->schedules->pluck('id');

            $myAttendances = Attendance::where('user_id', $request->user()->id)
                ->whereIn('schedule_id', $scheduleIds)
                ->with('schedule:id,meeting_number')
                ->get()
                ->keyBy('schedule_id');

            $assignmentIds = $practicum->assignments->pluck('id');

            $mySubmissions = AssignmentSubmission::where('user_id', $request->user()->id)
                ->whereIn('assignment_id', $assignmentIds)
                ->get()
                ->keyBy('assignment_id');

            $myEnrollment = Enrollment::where('user_id', $request->user()->id)
                ->where('practicum_id', $practicum->id)
                ->first();

            return view('students.practicum.show', [
                'practicum' => $practicum,
                'myAttendances' => $myAttendances,
                'mySubmissions' => $mySubmissions,
                'myEnrollment' => $myEnrollment,
            ]);
        }

        // Assistant
        $practicum->load(['course', 'academicYear', 'shift', 'enrollments', 'assignments', 'schedules']);

        return view('practicum.show', [
            'practicum' => $practicum,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Practicum::class);

        $validated = $request->validate([
            'course_id' => [
                'required',
                'exists:courses,id',
                // Ensure the combination of all three is unique
                Rule::unique('practicums')->where(function (Builder $query) use ($request) {
                    return $query->where('academic_year_id', $request->academic_year_id)
                        ->where('shift_id', $request->shift_id);
                }),
            ],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'shift_id' => ['required', 'exists:shifts,id'],
        ], [
            'course_id.unique' => 'The practicum for this course, academic year, and shift already exists.',
        ]);

        Practicum::create($validated);

        return redirect()->route('practicum.index')->with('success', 'Practicum opened successfully.');
    }

    public function destroy(Practicum $practicum)
    {
        Gate::authorize('delete', $practicum);

        try {
            $practicum->delete();

            return redirect()->route('practicum.index')->with('success', 'Practicum closed successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to close practicum. It may have related data that prevents deletion.');
        }
    }

    public function calculateScores(Practicum $practicum)
    {
        Gate::authorize('calculateScore', $practicum);

        // Pemrograman -> 1 Briefing + 8 Praktikum
        // APSI -> 1 Briefing + 5 Praktikum + 1 Presentasi
        $meetingNumbers = $practicum->course->id === 1 ? 9 : 7;
        $enrollments = $practicum->enrollments()
            // ->where('status', 'APPROVED')
            ->with('user.attendances')
            ->get();

        $scheduleIds = $practicum->schedules()->pluck('id');

        foreach ($enrollments as $enrollment) {
            $attendancesForThisPracticum = $enrollment->user->attendances->whereIn('schedule_id', $scheduleIds);

            $totalActiveScore = 0;
            $totalReportScore = 0;
            foreach ($attendancesForThisPracticum as $attendance) {
                $totalActiveScore += $attendance->active_score ?? 0;
                $totalReportScore += $attendance->report_score ?? 0;
            }

            $totalActiveScore = $this->calculateActiveScore($meetingNumbers, $totalActiveScore);
            $totalReportScore = ($totalReportScore / 8) * .65; // 8 Praktikum

            $finalScore = $totalActiveScore + $totalReportScore;

            $finalGrade = 'E';
            if ($finalScore >= 80) $finalGrade = 'A';
            elseif ($finalScore >= 77) $finalGrade = 'A-';
            elseif ($finalScore >= 74) $finalGrade = 'B+';
            elseif ($finalScore >= 68) $finalGrade = 'B';
            elseif ($finalScore >= 65) $finalGrade = 'B-';
            elseif ($finalScore >= 62) $finalGrade = 'C+';
            elseif ($finalScore >= 56) $finalGrade = 'C';
            elseif ($finalScore >= 45) $finalGrade = 'D';

            $enrollment->update([
                'final_active_score' => $totalActiveScore,
                'final_report_score' => $totalReportScore,
                'final_score'  => $finalScore,
                'final_grade'  => $finalGrade,
            ]);
        }

        return back()->with('success', 'Final scores have been successfully calculated and saved.');
    }

    private function calculateActiveScore(int $meetingNumbers, float $totalActiveScore): float
    {
        return ($totalActiveScore / $meetingNumbers) * .35;
    }
}
