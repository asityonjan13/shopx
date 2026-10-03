<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $admin = Admin::where('email', $request->string('email')->toString())->first();

        if (! $admin || ! Hash::check($request->string('password')->toString(), $admin->password)) {
            throw ValidationException::withMessages([
                'email' => 'Those credentials do not match an admin account.',
            ]);
        }

        $token = $admin->createToken('shopx-admin')->plainTextToken;

        return response()->json([
            'message' => 'Signed in.',
            'token' => $token,
            'data' => new AdminResource($admin),
        ]);
    }

    public function me(Request $request): AdminResource
    {
        return new AdminResource($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Signed out.']);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $admin = Admin::where('email', $request->string('email')->toString())->first();
        $payload = ['message' => 'If that email is registered, a reset link is ready.'];

        if ($admin && app()->environment('local')) {
            $payload['reset_token'] = Password::broker('admins')->createToken($admin);
        }

        return response()->json($payload);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin, string $password) {
                $admin->forceFill(['password' => $password])->save();
                $admin->tokens()->delete();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        return response()->json(['message' => 'Password updated.']);
    }
}
