<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function permissions(): JsonResponse
    {
        $permissions = Permission::query()
            ->where('guard_name', 'admin')
            ->orderBy('group_name')
            ->orderBy('name')
            ->get()
            ->groupBy('group_name')
            ->map(fn ($group) => $group->pluck('name')->values());

        return response()->json(['data' => $permissions]);
    }

    public function index(): JsonResponse
    {
        $roles = Role::query()->where('guard_name', 'admin')->with('permissions')->withCount('permissions')->orderBy('name')->get();

        return response()->json([
            'data' => RoleResource::collection($roles)->resolve(),
        ]);
    }

    public function store(RoleRequest $request): JsonResponse
    {
        $role = Role::create([
            'name' => $request->string('name')->toString(),
            'guard_name' => 'admin',
        ]);
        $role->syncPermissions($request->input('permissions'));

        return response()->json([
            'message' => 'Role created.',
            'data' => new RoleResource($role->load('permissions')),
        ], 201);
    }

    public function show(Role $role): RoleResource
    {
        abort_unless($role->guard_name === 'admin', 404);

        return new RoleResource($role->load('permissions'));
    }

    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        abort_unless($role->guard_name === 'admin', 404);

        if ($role->name === 'Super Admin') {
            return response()->json(['message' => 'The Super Admin role cannot be changed.'], 422);
        }

        $role->update(['name' => $request->string('name')->toString()]);
        $role->syncPermissions($request->input('permissions'));

        return response()->json([
            'message' => 'Role updated.',
            'data' => new RoleResource($role->load('permissions')),
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        abort_unless($role->guard_name === 'admin', 404);

        if ($role->name === 'Super Admin') {
            return response()->json(['message' => 'The Super Admin role cannot be deleted.'], 422);
        }

        DB::transaction(function () use ($role) {
            $role->users()->detach();
            $role->permissions()->detach();
            $role->delete();
        });

        return response()->json(['message' => 'Role deleted.']);
    }
}
