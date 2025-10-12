<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CheckPasswordResetToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $duration = config('auth.passwords.users.expire');
        $hashedToken = $request->email ? DB::table('password_reset_tokens')
            ->where('email', $request->email)->first() : null;
        $expired = $hashedToken ? Carbon::parse($hashedToken->created_at)
            ->addMinutes($duration)->isPast() : null;
        $tokenValid = $hashedToken && !$expired && $request->token 
            ? Hash::check($request->token, $hashedToken->token) : null;
        if (!$tokenValid) {
            return response()->view('message', [
                'message' => 'This password reset link is invalid.'
            ]);
        }
        return $next($request);
    }
}
