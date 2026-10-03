<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StaffRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index(): JsonResponse
    {
        $staff = Admin::query()->with('roles')->orderBy('name')->get();

        return response()->json([
            'data' => AdminResource::collection($staff)->resolve(),
        ]);
    }

    public function store(StaffRequest $request): JsonResponse
    {
        $role = $this->role($request);

        if ($role->name === 'Super Admin') {
            return response()->json(['message' => 'Create staff with a role other than Super Admin.'], 422);
        }

        $admin = Admin::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($role);

        return response()->json([
            'message' => 'Staff account created.',
            'data' => new AdminResource($admin),
        ], 201);
    }

    public function update(StaffRequest $request, Admin $staff): JsonResponse
    {
        if ($staff->hasRole('Super Admin')) {
            return response()->json(['message' => 'The Super Admin account cannot be reassigned here.'], 422);
        }

        $role = $this->role($request);
        if ($role->name === 'Super Admin') {
            return response()->json(['message' => 'That role cannot be assigned from this screen.'], 422);
        }

        $staff->name = $request->string('name')->toString();
        $staff->email = $request->string('email')->toString();
        if ($request->filled('password')) {
            $staff->password = $request->string('password')->toString();
        }
        $staff->save();
        $staff->syncRoles([$role]);

        return response()->json([
            'message' => 'Staff account updated.',
            'data' => new AdminResource($staff),
        ]);
    }

    public function destroy(Request $request, Admin $staff): JsonResponse
    {
        if ($staff->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot delete the account you are using.'], 422);
        }

        if ($staff->hasRole('Super Admin')) {
            return response()->json(['message' => 'The Super Admin account cannot be deleted.'], 422);
        }

        $staff->syncRoles([]);
        $staff->tokens()->delete();
        $staff->delete();

        return response()->json(['message' => 'Staff account deleted.']);
    }

    private function role(StaffRequest $request): Role
    {
        return Role::query()->where('guard_name', 'admin')->findOrFail($request->integer('role_id'));
    }
}
