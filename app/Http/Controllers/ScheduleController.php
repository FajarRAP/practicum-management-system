<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        Gate::authorize('create', Schedule::class);

        $validated = $request->validateWithBag('addSchedule', [
            'practicum_id' => ['required', 'exists:practicums,id'],
            'meeting_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('schedules')->where('practicum_id', $request->practicum_id)
            ],
            'topic' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s,H:i'],
            'end_time' => ['required', 'date_format:H:i:s,H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        Schedule::create($validated);

        return back()->with('success', 'Schedule added successfully.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        Gate::authorize('update', Schedule::class);

        $validated = $request->validateWithBag('updateSchedule', [
            'meeting_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('schedules')->where('practicum_id', $schedule->practicum_id)->ignore($schedule->id)
            ],
            'topic' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s,H:i'],
            'end_time' => ['required', 'date_format:H:i:s,H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $schedule->update($validated);

        return back()->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        Gate::authorize('delete', Schedule::class);

        try {
            $schedule->delete();
            return back()->with('success', 'Schedule deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to delete schedule. It may have related data that prevents deletion.');
        }
    }

    public function approve(Request $request, Schedule $schedule)
    {
        Gate::authorize('approve', Schedule::class);

        $schedule->update([
            'status' => 'APPROVED',
            'processed_by' => $request->user()->id,
            'processed_at' => now(),
            'rejection_reason' => null
        ]);

        return back()->with('success', 'Schedule has been approved.');
    }

    public function reject(Request $request, Schedule $schedule)
    {
        Gate::authorize('reject', Schedule::class);

        $request->validateWithBag('rejectSchedule', [
            'rejection_reason' => ['nullable', 'string', 'max:500']
        ]);

        $schedule->update([
            'status' => 'REJECTED',
            'processed_by' => $request->user()->id,
            'processed_at' => now(),
            'rejection_reason' => $request->rejection_reason
        ]);

        return back()->with('success', 'Schedule has been rejected.');
    }
}
