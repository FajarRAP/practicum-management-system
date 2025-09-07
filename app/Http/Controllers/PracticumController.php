<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Practicum;
use App\Models\Shift;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PracticumController extends Controller
{
    public function index(Request $request)
    {
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

    public function show(Practicum $practicum)
    {
        return view('practicum-detail', [
            'practicum' => $practicum->load(['course', 'academicYear', 'shift', 'enrollments']),
        ]);
    }

    public function store(Request $request)
    {
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
        try {
            $practicum->delete();

            return redirect()->route('practicum.index')->with('success', 'Practicum closed successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to close practicum. It may have related data that prevents deletion.');
        }
    }
}
