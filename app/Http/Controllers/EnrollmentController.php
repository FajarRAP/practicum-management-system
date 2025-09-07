<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Practicum;
use App\Models\Schedule;
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
        // $validated = $request->validateWithBag('addEnrollment', [
        //     'schedule' => ['required'],
        //     'study_plan' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        //     'transcript' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        //     'photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        // ]);

        // $user = $request->user();

        // if (!$user->identity_number) {
        //     return back()->with('error', 'You must complete your student identity before enrolling.');
        // }

        // $schedule = Schedule::find($validated['schedule']);

        // if ($user->enrollments->where(fn($enrollment) => $enrollment->schedule->course->id == $schedule->course->id)->isNotEmpty()) {
        //     return back()->with('error', 'You are already enrolled in a practicum for this course.');
        // }

        // if ($user->enrollments()->where('schedule_id', $validated['schedule'])->exists()) {
        //     return back()->with('error', 'You are already enrolled in this schedule.');
        // }

        // Enrollment::create([
        //     'user_id' => $user->id,
        //     'schedule_id' => $validated['schedule'],
        //     'study_plan_path' => $request->file('study_plan')->store('enrollments', 'public'),
        //     'transcript_path' => $request->file('transcript')->store('enrollments', 'public'),
        //     'photo_path' => $request->file('photo')->store('enrollments', 'public'),
        // ]);

        // return back()->with('success', 'Successfully enrolled in practicum.');
    }
}
