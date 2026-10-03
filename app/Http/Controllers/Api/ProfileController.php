<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PasswordUpdateRequest;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Http\Resources\AdminResource;
use App\Http\Resources\UserResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $account = $request->user();
        $account->name = $request->string('name')->toString();
        $account->email = $request->string('email')->toString();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $this->deleteStoredAvatar($account->avatar);
            $account->avatar = $path;
        }

        $account->save();

        $resource = $account instanceof Admin
            ? new AdminResource($account)
            : new UserResource($account->load(['kyc', 'store']));

        return response()->json([
            'message' => 'Profile updated.',
            'data' => $resource,
        ]);
    }

    public function updatePassword(PasswordUpdateRequest $request): JsonResponse
    {
        $account = $request->user();
        $account->password = $request->string('password')->toString();
        $account->save();

        return response()->json(['message' => 'Password updated.']);
    }

    private function deleteStoredAvatar(?string $path): void
    {
        if ($path && ! str_starts_with($path, '/') && ! str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
