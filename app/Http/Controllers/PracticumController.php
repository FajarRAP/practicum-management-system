<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Practicum;
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
        $practicums = Practicum::with(['course', 'academicYear'])->latest()->paginate($perPage);

        return view('practicum', [
            'courses' => $courses,
            'academicYears' => $academicYears,
            'practicums' => $practicums,
        ]);
    }

    public function show(Practicum $practicum)
    {
        // $data = Practicum::with([
        //     'course',
        //     'academicYear',
        //     // 'enrollments.student', // Contoh relasi ke mahasiswa
        //     // 'schedules',           // Contoh relasi ke jadwal
        //     // 'assignments'          // Contoh relasi ke tugas
        // ])->findOrFail($practicum->id);

        return view('practicum-detail', [
            'practicum' => $practicum->load(['course', 'academicYear']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('practicums')->where(
                    fn(Builder $query)
                    => $query->where('academic_year_id', $request->academic_year_id)
                ),
            ],
            'academic_year_id' => 'required|exists:academic_years,id',
        ], [
            'course_id.unique' => 'A practicum for this course in the selected academic year already exists.',
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
