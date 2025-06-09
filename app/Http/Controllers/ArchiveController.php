<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $archive = Archive::all();

        return view('archive', ['archives' => $archive]);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addArchive', [
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf'],
        ]);

        Archive::create([
            'title' => $validated['title'],
            'file_path' => $request->file('file')->store('archives', 'public'),
        ]);

        return back()->with('success', 'Archive added successfully');
    }
}
