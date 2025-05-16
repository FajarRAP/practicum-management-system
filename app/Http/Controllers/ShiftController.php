<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        return view('assistants.shift', [
            'courses' => Course::all(),
        ]);
    }

    public function store(Request $request) {}
}
