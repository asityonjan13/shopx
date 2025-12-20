<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\AlertService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllersMiddleware;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserRoleController extends Controller implements HasMiddleware
{
    static function Middleware() : array
    {
        return [
            new ControllersMiddleware('permission:Role User Management')
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $admins = Admin::all();
        return view('admin.role-user.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //get all the roles to pass in blade
        $roles = Role::all();
        return view('admin.role-user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required']
        ]);
        $role = Role::findOrFail($request->role);

        if($role->name=="Super Admin"){
            AlertService::error("You cannot create super Admin User");
            return to_route('admin.role-users.index');
        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        $admin->save();

        // Assign role to admin
        $admin->assignRole($role);

        AlertService::created();
        return to_route('admin.role-users.index');
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
    public function edit(Admin $role_user)
    {
        $admin = $role_user;
        $roles = Role::all();
        return view('admin.role-user.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $role_user)
    {
        if ($role_user->hasRole('Super Admin')) {
            AlertService::error('You cannot update role of Super Admin User');
            return to_route('admin.role-users.index');
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,' . $role_user->id],
            'role' => ['required']
        ]);
        $role = Role::findOrFail($request->role);
        $admin = $role_user;
        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', 'min:8']
            ]);
            $admin->password = bcrypt($request->password);
        }
        $admin->save();

        // Sync roles (use syncRoles instead of assignRole to avoid duplicates)
        $admin->syncRoles($role);

        AlertService::updated();
        return to_route('admin.role-users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $role_user): JsonResponse
    {
        if ($role_user->hasRole('Super Admin')) {
            AlertService::error('You cannot change role of Super Admin');
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot delete Super Admin User'
            ], 403);
        }

        try {
            // Remove all roles from the user
            foreach ($role_user->getRoleNames() as $role) {
                $role_user->removeRole($role);
            }

            $role_user->delete();
            AlertService::deleted('Role user deleted successfully');
            return response()->json([
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            // Log the error for debugging
            \Log::error('Failed to delete role user: ' . $th->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete role user'
            ], 500);
        }
    }
}
