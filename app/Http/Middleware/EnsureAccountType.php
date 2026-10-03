<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        $account = $request->user();

        $allowed = match ($type) {
            'admin' => $account instanceof Admin,
            'user' => $account instanceof User,
            'vendor' => $account instanceof User && $account->user_type === 'vendor',
            default => false,
        };

        if (! $allowed) {
            return response()->json([
                'message' => 'You do not have access to this resource.',
            ], 403);
        }

        return $next($request);
    }
}
