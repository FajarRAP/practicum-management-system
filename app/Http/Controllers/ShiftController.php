<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $shifts = Shift::latest()
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]);

        return view('shift.index', [
            'shifts' => $shifts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:shifts,name'],
        ]);

        Shift::create($validated);

        return back()->with('success', 'Shift created successfully.');
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', "unique:shifts,name,{$shift->id}"],
        ]);

        $shift->update($validated);

        return back()->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        try {
            $shift->delete();

            return back()->with('success', 'Shift deleted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to delete shift. It may be associated with other records.');
        }
    }
}
