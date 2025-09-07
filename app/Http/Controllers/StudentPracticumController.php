<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StudentPracticumController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $myPracticums = $user->enrollments()
            // ->where('status', 'APPROVED')
            ->with([
                'practicum.course',
                'practicum.academicYear',
                'practicum.shift',
            ])
            ->whereHas('practicum.academicYear', function (Builder $query) {
                $query->where('status', 'ACTIVE');
            })
            ->get()->pluck('practicum');

        return view('students.my-practicum.index', compact('myPracticums'));
    }
}
