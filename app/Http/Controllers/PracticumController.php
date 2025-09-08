<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AssignmentSubmission;
use App\Models\Attendance;
use App\Models\Course;
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
        $practicums = Practicum::with(['course', 'academicYear', 'shift'])->latest()->paginate($perPage);

        return view('practicum', [
            'academicYears' => $academicYears,
            'courses' => $courses,
            'shifts' => $shifts,
            'practicums' => $practicums,
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
                ->get()
                ->keyBy('schedule_id');

            $assignmentIds = $practicum->assignments->pluck('id');

            $mySubmissions = AssignmentSubmission::where('user_id', $request->user()->id)
                ->whereIn('assignment_id', $assignmentIds)
                ->get()
                ->keyBy('assignment_id');

            return view('students.practicum.show', [
                'practicum' => $practicum,
                'myAttendances' => $myAttendances,
                'mySubmissions' => $mySubmissions,
            ]);
        }

        // Assistant
        $practicum->load(['course', 'academicYear', 'shift', 'enrollments', 'assignments', 'schedules']);
        return view('practicum-detail', [
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
        Gate::authorize('delete', Practicum::class);

        try {
            $practicum->delete();

            return redirect()->route('practicum.index')->with('success', 'Practicum closed successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to close practicum. It may have related data that prevents deletion.');
        }
    }
}
