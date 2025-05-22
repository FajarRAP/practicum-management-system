<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function edit(Request $request)
    {
        $validated = $request->validate([
            'student_number' => ['nullable', 'string'],
            'study_plan' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'transcript' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);
        $storage = Storage::disk('public');

        $studyPlanFile = $request->file('study_plan');
        if ($studyPlanFile && $request->user()->student?->study_plan_path) {
            $storage->delete($request->user()->student->study_plan_path);
        }

        $transcriptFile = $request->file('transcript');
        if ($transcriptFile && $request->user()->student?->transcript_path) {
            $storage->delete($request->user()->student->transcript_path);
        }

        $photoFile = $request->file('photo');
        if ($photoFile && $request->user()->student?->photo_path) {
            $storage->delete($request->user()->student->photo_path);
        }

        $studentPlanPath = $studyPlanFile ?
            $studyPlanFile->store('student_identity/' . $request->user()->id, 'public') :
            $request->user()->student->study_plan_path ?? null;
        $transcriptPath = $transcriptFile ?
            $transcriptFile->store('student_identity/' . $request->user()->id, 'public') :
            $request->user()->student->transcript_path ?? null;
        $photoPath = $photoFile ?
            $photoFile->store('student_identity/' . $request->user()->id, 'public') :
            $request->user()->student->photo_path ?? null;

        Student::upsert([
            'user_id' => $request->user()->id,
            'student_number' => $validated['student_number'],
            'study_plan_path' => $studentPlanPath,
            'transcript_path' => $transcriptPath,
            'photo_path' => $photoPath,
        ], ['user_id'], ['student_number', 'study_plan_path', 'transcript_path', 'photo_path']);

        return back()->with('success', 'Student updated successfully.');
    }
}
