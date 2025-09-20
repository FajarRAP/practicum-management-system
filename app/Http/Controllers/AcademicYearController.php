<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertAcademicYearRequest;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        return view('academic-year.index', [
            'academicYears' => AcademicYear::latest()->paginate($perPage)->appends(['per_page' => $perPage]),
        ]);
    }

    public function store(UpsertAcademicYearRequest $request)
    {
        AcademicYear::create($request->validated());

        return redirect()->route('academic-year.index')->with('success', 'Academic Year created successfully.');
    }

    public function update(UpsertAcademicYearRequest $request, AcademicYear $academicYear)
    {
        $academicYear->update($request->validated());

        return redirect()->route('academic-year.index')->with('success', 'Academic Year updated successfully.');
    }

    public function destroy(Request $request, AcademicYear $academicYear)
    {
        try {
            $academicYear->delete();
            return redirect()->route('academic-year.index')->with('success', 'Academic Year deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error occurred when deleting Academic Year.');
        }
    }
}
