<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Practicum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function store(Request $request)
    {
        // Authorize here

        $validated = $request->validateWithBag('addAssignment', [
            'practicum_id' => ['required', 'exists:practicums,id'],
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
        // Authorize here

        $validated = $request->validateWithBag('updateAssignment', [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf,zip,doc,docx', 'max:10240'],
        ]);

        if ($request->hasFile('file')) {
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }

            $validated['file_path'] = $request->file('file')->store('assignments', 'public');
        }

        $assignment->update($validated);

        return back()->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Practicum $practicum, Assignment $assignment)
    {
        // Authorize here

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
    // public function index(Request $request)
    // {

    //     $perPage = $request->query('per_page', 10);
    //     $assignments = Assignment::query();
    //     $announcements = Announcement::query();
    //     $assistantDatas = [
    //         'announcements' => $announcements->where(['is_schedule_announcement' => true, 'is_approved' => true])->get(),
    //         'assignments' => $assignments->paginate($perPage)->appends(['per_page' => $perPage]),
    //     ];

    //     $studentDatas = [
    //         'assignments' => $assignments
    //             ->join('announcements', 'assignments.announcement_id', '=', 'announcements.id')
    //             ->join('enrollments', 'announcements.schedule_id', '=', 'enrollments.schedule_id')
    //             ->select('assignments.*', 'enrollments.user_id')
    //             ->where('enrollments.user_id', $request->user()->id)
    //             ->paginate($perPage)
    //             ->appends(['per_page' => $perPage]),
    //     ];

    //     return $request->user()->hasRole('student') ?
    //         view('students.assignment', $studentDatas) :
    //         view('assistants.assignment', $assistantDatas);
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validateWithBag('addAssignment', [
    //         'announcement' => ['required', 'exists:announcements,id'],
    //         'title' => ['required', 'string', 'max:255'],
    //         'due_date' => ['required', 'date'],
    //     ]);

    //     Assignment::create([
    //         'announcement_id' => $validated['announcement'],
    //         'title' => $validated['title'],
    //         'due_date' => $validated['due_date'],
    //     ]);

    //     return back()->with('success', 'Assignment created successfully.');
    // }
}
