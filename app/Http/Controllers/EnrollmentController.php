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
            'schedule' => ['required'],
            'study_plan' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'transcript' => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();

        if (!$user->identity_number) {
            return back()->with('error', 'You must complete your student identity before enrolling.');
        }

        if ($user->enrollments->isNotEmpty()) {
            return back()->with('error', 'You are already enrolled practicum in this semester.');
        }

        if ($user->enrollments()->where('schedule_id', $validated['schedule'])->exists()) {
            return back()->with('error', 'You are already enrolled in this schedule.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'schedule_id' => $validated['schedule'],
            'study_plan_path' => $request->file('study_plan')->store('enrollments', 'public'),
            'transcript_path' => $request->file('transcript')->store('enrollments', 'public'),
            'photo_path' => $request->file('photo')->store('enrollments', 'public'),
        ]);

        return back()->with('success', 'Successfully enrolled in practicum.');
    }
}
