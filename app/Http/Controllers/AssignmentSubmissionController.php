<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AssignmentSubmissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assignment_id' => ['required', 'exists:assignments,id'],
            'submission_file' => ['required', 'file', 'mimes:pdf,zip,rar', 'max:10240'],
            'comments' => ['nullable', 'string', 'max:1000'],
        ]);

        $assignment = Assignment::find($validated['assignment_id']);
        $user = $request->user();


        Gate::authorize('view', $assignment->practicum);

        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($existingSubmission) {
            return back()->with('error', 'You have already submitted this assignment.');
        }

        $isLate = now()->gt($assignment->deadline);

        $filePath = $request->file('submission_file')->store("submissions/{$assignment->id}", 'public');

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $user->id,
            'file_path' => $filePath,
            'is_late' => $isLate,
            // 'comments' perlu ditambahkan ke migrasi jika Anda mau menyimpan komentar
        ]);

        return back()->with('success', 'Assignment submitted successfully.');
    }

    // public function index(Assignment $assignment)
    // {
    //     return view('assistants.assignment-submission', [
    //         'assignment' => $assignment,
    //         'submissions' => $assignment->submissions,
    //     ]);
    // }
    // public function store(Request $request, Assignment $assignment)
    // {
    //     $request->validateWithBag('submitAssignment', [
    //         'file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
    //     ]);

    //     AssignmentSubmission::create([
    //         'assignment_id' => $assignment->id,
    //         'user_id' => $request->user()->id,
    //         'file_path' => $request->file('file')->store('submissions', 'public'),
    //         'submitted_at' => now(),
    //     ]);

    //     return back()->with('success', 'Assignment submitted successfully.');
    // }
}
