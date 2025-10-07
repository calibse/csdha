<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CheckPasswordResetToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $hashedToken = $request->email ? DB::table('password_reset_tokens')
            ->where('email', $request->email)->value('token') : null;
        $tokenValid = $hashedToken && $request->token 
            ? Hash::check($request->token, $hashedToken) : null;
        if (!$tokenValid) {
            return view('message', [
                'message' => 'This password reset link is invalid.'
            ]);
        }
        return $next($request);
    }
}
