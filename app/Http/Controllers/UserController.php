<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Ambil semua user, paginasi, dan eager load relasi roles
        $users = User::with('roles')->latest()->paginate(15);
        // Ambil semua role untuk mengisi checkbox di modal
        $roles = Role::all();

        return view('user-management', compact('users', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Otorisasi: pastikan user yang login adalah admin
        // Gate::authorize('update', $user);

        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        // Gunakan syncRoles dari Spatie untuk update. Sangat efisien!
        $user->syncRoles($validated['roles'] ?? []);

        return back()->with('success', "User roles for {$user->name} have been updated.");
    }
}
