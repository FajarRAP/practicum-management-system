<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        return  view('assistants.attendance', [
            'announcements' => Announcement::paginate($perPage)
                ->appends(['per_page' => $perPage]),
        ]);
    }

    public function show(Announcement $announcement)
    {
        return view('assistants.attendance-show', [
            'assistants' => User::role('assistant')->get(),
            'announcement' => $announcement,
        ]);
    }

    public function store(Request $request, Announcement $announcement)
    {
        $users = $request->input('users', []);

        $mappedUsers = [];
        foreach ($users as $userId => $status) {
            $mappedUsers[] = [
                'announcement_id' => $announcement->id,
                'user_id' => $userId,
                'status' => $status,
            ];
        }

        Attendance::upsert(
            $mappedUsers,
            ['announcement_id', 'user_id'],
            ['status']
        );

        return back()->with('success', 'Attendance marked successfully.');
    }
}
