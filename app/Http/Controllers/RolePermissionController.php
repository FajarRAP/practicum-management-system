<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy(fn($permission)
        => explode('.', $permission->name)[0]);
        

        $activeRole = $roles->firstWhere('id', $request->query('role')) ?? $roles->first();
        $rolePermissions = $activeRole ? $activeRole->permissions->pluck('name') : collect();

        return view('role-management', [
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
            'activeRole' => $activeRole,
        ]);
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => ['present', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name']
        ]);

        $role->syncPermissions($validated['permissions']);

        return back()->with('success', 'Permissions updated successfully.');
    }
}
