<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $assignments = Assignment::paginate($perPage)->appends(['per_page' => $perPage]);

        $assistantDatas = [
            'assignments' => $assignments,
        ];

        return $request->user()->hasRole('student') ?
            view('students.assignment') :
            view('assistants.assignment', $assistantDatas);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addAssignment', [
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        Assignment::create($validated);

        return back()->with('success', 'Assignment created successfully.');
    }
}
