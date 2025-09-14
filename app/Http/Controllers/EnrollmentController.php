<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Practicum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $enrolledPracticumIds = $user->enrollments->pluck('practicum_id');
        $practicums = Practicum::with(['course', 'academicYear', 'shift'])
            ->whereHas('academicYear', function (Builder $query) {
                $query->where('status', 'ACTIVE');
            })
            ->whereNotIn('id', $enrolledPracticumIds)
            ->get();
        $enrollments = $user->enrollments()->with(['practicum.course', 'practicum.academicYear', 'practicum.shift'])->get();

        return view('students.enrollment', [
            'enrollments' => $enrollments,
            'practicums' => $practicums,
        ]);
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $validated = $request->validated();

        Enrollment::create([
            'user_id' => $request->user()->id,
            'practicum_id' => $validated['practicum_id'],
            'study_plan_path' => $request->file('study_plan')->store('enrollments', 'public'),
            'transcript_path' => $request->file('transcript')->store('enrollments', 'public'),
            'photo_path' => $request->file('photo')->store('enrollments', 'public'),
        ]);

        return back()->with('success', 'Successfully enrolled in practicum.');
    }
}
