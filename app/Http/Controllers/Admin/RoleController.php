<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AlertService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllersMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    static function Middleware() : array
    {
        return [
            new ControllersMiddleware('permission:Role Management')
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $roles = Role::withCount('permissions')->get();
        // dd($roles);
        return view('admin.role.index', compact('roles'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $permissions = Permission::all()->groupBy('group_name');
        // dd($permissions);
        return view('admin.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'role' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array']

        ]);
        $role = Role::create(['name' => $request->role, 'guard_name' => 'admin']);
        $role->syncPermissions($request->permissions);
        AlertService::created();
        return to_route('admin.role.index');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('group_name');
        return view('admin.role.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // dd($request->all());
        if($role->name == "Super Admin"){
            AlertService::error('You cannot change permission of Super Admin');
            return to_route('admin.role.index');
        }

        $validate = $request->validate([
            'role' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['required', 'array']

        ]);

        $role->update(['name' => $request->role]);
        $role->syncPermissions($request->permissions);
        AlertService::updated();
        return to_route('admin.role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        // dd($role);
        if($role->name == "Super Admin"){
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot delete role named Super Admin'
            ]);
        }
        try {
            DB::beginTransaction();
            //remove role user
            $role->users()->detach();
            //detach permission from role
            $role->permissions()->detach();
            $role->delete();
            DB::commit();
            AlertService::deleted('Role has been deleted successfully');
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Error while deleting role", ['error' => $th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
