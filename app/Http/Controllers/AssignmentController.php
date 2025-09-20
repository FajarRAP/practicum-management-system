<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Practicum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function store(Request $request)
    {
        Gate::authorize('create', Assignment::class);

        $validated = $request->validateWithBag('addAssignment', [
            'practicum_id' => ['required', 'exists:practicums,id'],
            'schedule_id' => ['required', 'exists:schedules,id', 'unique:assignments,schedule_id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,zip,doc,docx', 'max:10240'],
        ]);

        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('assignments', 'public');
        }

        Assignment::create($validated);

        return back()->with('success', 'Assignment created successfully.');
    }

    public function update(Request $request, Practicum $practicum, Assignment $assignment)
    {
        Gate::authorize('update', $assignment);

        $validated = $request->validateWithBag('updateAssignment', [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,zip,doc,docx', 'max:10240'],
        ]);

        if ($request->hasFile('file_path')) {
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }

            $validated['file_path'] = $request->file('file_path')->store('assignments', 'public');
        }

        $assignment->update($validated);

        return back()->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Practicum $practicum, Assignment $assignment)
    {
        Gate::authorize('delete', $assignment);

        try {
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }

            $assignment->delete();

            return back()->with('success', 'Assignment deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to delete assignment. It may have related submissions.');
        }
    }
}
