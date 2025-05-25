<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;

class AssignmentSubmissionController extends Controller
{
    public function index(Request $request, Assignment $assignment)
    {
        return view('assistants.assignment-submission', [
            'assignment' => $assignment,
            'submissions' => $assignment->submissions,
        ]);
    }

    public function store(Request $request, Assignment $assignment)
    {
        $request->validateWithBag('submitAssignment', [
            'file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $request->user()->id,
            'file_path' => $request->file('file')->store('submissions', 'public'),
        ]);

        return back()->with('success', 'Assignment submitted successfully.');
    }
}
