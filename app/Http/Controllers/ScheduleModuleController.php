<?php

namespace App\Http\Controllers;

use App\Models\ScheduleModule;
use Illuminate\Http\Request;

class ScheduleModuleController extends Controller
{
    public function index(Request $request)
    {
        $modules = ScheduleModule::all();

        return $request->user()->hasRole('student') ?
            view('students.schedule-module', [
                'modules' => $modules,
            ]) :
            view('assistants.schedule-module', [
                'modules' => $modules,
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validateWithBag('addModule', [
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ]);

        ScheduleModule::create([
            'title' => $validated['title'],
            'file_path' => $request->file('file')->store('schedule-modules', 'public'),
        ]);

        return back()->with('success', 'Module added successfully.');
    }
}
