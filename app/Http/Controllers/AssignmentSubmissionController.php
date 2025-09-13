<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Practicum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AssignmentSubmissionController extends Controller
{
    public function index(Practicum $practicum, Assignment $assignment)
    {
        // Authorize here

        $enrollments = $assignment->practicum->enrollments()
            // ->where('status', 'APPROVED')
            ->with('user')
            ->get();

        $submissions = $assignment->submissions->keyBy('user_id');

        return view('assignment-submission.index', [
            'assignment' => $assignment,
            'enrollments' => $enrollments,
            'submissions' => $submissions,
        ]);
    }

    public function store(Request $request, Assignment $assignment)
    {
        $validated = $request->validateWithBag('addSubmission', [
            'assignment_id' => ['required', 'exists:assignments,id'],
            'submission_file' => ['required', 'file', 'mimes:pdf,zip,rar', 'max:10240'],
            'comments' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

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
}
