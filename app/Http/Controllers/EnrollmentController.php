<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Schedule;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        return view('students.enrollment', [
            'enrollments' => $request->user()->enrollments,
            'schedules' => Schedule::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addEnrollment', [
            'schedule' => ['required', 'exists:schedules,id'],
        ]);

        $user = $request->user();

        if (
            !$user->student ||
            !$user->student->student_number ||
            !$user->student->study_plan_path ||
            !$user->student->transcript_path ||
            !$user->student->photo_path
        ) {
            return back()->with('error', 'You must complete your student identity before enrolling.');
        }

        if ($user->enrollments()->where('schedule_id', $validated['schedule'])->exists()) {
            return back()->with('error', 'You are already enrolled in this schedule.');
        }

        Enrollment::create([
            'user_id' => $request->user()->id,
            'schedule_id' => $validated['schedule'],
        ]);

        return back()->with('success', 'Enrollment created successfully.');
    }
}
