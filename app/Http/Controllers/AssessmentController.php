<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assessment;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        return view('assistants.assessment', [
            'announcements' => Announcement::paginate($perPage)->appends(['per_page' => $perPage]),
        ]);
    }

    public function show(Request $request, Announcement $announcement)
    {
        return view('assistants.assessment-show', [
            'announcement' => $announcement,
        ]);
    }

    public function store(Request $request)
    {

        return back()->with('success', 'Assessment created successfully.');
    }
}
