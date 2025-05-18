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

        if ($request->user()->enrollments()->where('schedule_id', $validated['schedule'])->exists()) {
            return back()->with('error', 'You are already enrolled in this schedule.');
        }

        Enrollment::create([
            'user_id' => $request->user()->id,
            'schedule_id' => $validated['schedule'],
        ]);

        return back()->with('success', 'Enrollment created successfully.');
    }
}
