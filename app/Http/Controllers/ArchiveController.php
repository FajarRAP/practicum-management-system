<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $archive = Archive::all();

        return $request->user()->hasRole('student') ?
            view('students.archive', [
                'archive' => $archive,
            ]) :
            view('assistants.archive', [
                'archive' => $archive,
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addModule', [
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        Archive::create([
            'title' => $validated['title'],
            'file_path' => $request->file('file')->store('archives', 'public'),
        ]);

        return back()->with('success', 'Module added successfully.');
    }
}
