<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::where('user_type', '<>', 1)->get();
        return view('roles.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        $permissions = \Spatie\Permission\Models\Permission::orderBy('module')
            ->get()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        $role->syncPermissions($request->permissions);

        return redirect()->route(routePrefix().'roles.index')->with('success', 'Permissions updated successfully.');
    }
}
