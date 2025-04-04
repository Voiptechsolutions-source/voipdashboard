<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function getPermissions($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role->permissions);
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        if ($request->permissions) {
            $role->permissions()->attach($request->permissions);
        }
        return redirect()->route('roles.index');
    }

    public function assignRole(Request $request, User $user)
    {
        $user->update(['role_id' => $request->role_id]);
        return redirect()->back();
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissionsData = $request->input('permissions', []);
        Log::info('Permissions Data Received:', $permissionsData);

        $syncData = [];
        foreach ($permissionsData as $permissionId => $abilities) {
            $syncData[$permissionId] = [
                'can_view' => isset($abilities['can_view']) ? 1 : 0,
                'can_edit' => isset($abilities['can_edit']) ? 1 : 0,
                'can_delete' => isset($abilities['can_delete']) ? 1 : 0,
            ];
        }
        Log::info('Sync Data:', $syncData);

        if (!empty($syncData)) {
            $role->permissions()->sync($syncData);
        }
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['success' => true, 'message' => 'Role deleted successfully']);
    }

    public function getRoles()
    {
        try {
            $roles = Role::select('id', 'name')->get();
            Log::info('Roles fetched for API:', $roles->toArray());
            return response()->json(['success' => true, 'roles' => $roles]);
        } catch (\Exception $e) {
            Log::error('Error fetching roles:', ['message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}