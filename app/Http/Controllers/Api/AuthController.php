<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
            'user_type' => $request->string('user_type')->toString(),
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('shopx')->plainTextToken;

        return response()->json([
            'message' => 'Account created.',
            'token' => $token,
            'data' => new UserResource($user->load(['kyc', 'store'])),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->string('email')->toString())->first();

        if (! $user || ! Hash::check($request->string('password')->toString(), $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Those credentials do not match our records.',
            ]);
        }

        $token = $user->createToken('shopx')->plainTextToken;

        return response()->json([
            'message' => 'Signed in.',
            'token' => $token,
            'data' => new UserResource($user->load(['kyc', 'store'])),
        ]);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user()->load(['kyc', 'store']));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Signed out.']);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->string('email')->toString())->first();
        $payload = [
            'message' => 'If that email is registered, a reset link is ready.',
        ];

        if ($user && app()->environment('local')) {
            $payload['reset_token'] = Password::broker('users')->createToken($user);
        }

        return response()->json($payload);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => $password])->save();
                $user->tokens()->delete();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => __($status),
            ]);
        }

        return response()->json(['message' => 'Password updated. You can sign in with the new password.']);
    }
}
