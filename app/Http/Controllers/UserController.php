<?php

namespace App\Http\Controllers;

use App\Models\Practicum;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $users = User::query();
        $users->when($request->query('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
        $users->when($request->query('role'), function ($query, $roleId) {
            $query->whereHas('roles', fn($roleQuery) => $roleQuery->where('id', $roleId));
        });

        $users = $users->with('roles')
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        $roles = Role::orderBy('name')->get();

        $practicums = Practicum::with(['course', 'shift'])->whereHas('academicYear', function ($query) {
            $query->where('status', 'ACTIVE');
        })->get();

        return view('user-management', [
            'users' => $users,
            'practicums' => $practicums,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        // Otorisasi: pastikan user yang login adalah admin
        // Gate::authorize('update', $user);

        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($validated['roles'] ?? []);

        return back()->with('success', "User roles for {$user->name} have been updated.");
    }

    public function updatePracticumAssignments(Request $request, User $user)
    {
        // Otorisasi: Pastikan hanya admin yang bisa, dan targetnya adalah dosen/asisten

        if (!$user->hasAnyRole(['lecturer', 'assistant'])) {
            return back()->with('error', 'Only lecturers and assistants can be assigned to practicums.');
        }

        $validated = $request->validate([
            'practicums' => ['nullable', 'array'],
            'practicums.*' => ['integer', 'exists:practicums,id'],
        ]);

        $user->practicums()->sync($validated['practicums'] ?? []);

        return back()->with('success', "Practicum assignments for {$user->name} have been updated.");
    }
}
