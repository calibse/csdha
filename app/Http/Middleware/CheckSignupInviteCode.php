<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSignupInviteCode
{
    public function handle(Request $request, Closure $next): Response
    {
        $inviteCode = session('inviteCode') ?? $request->invite_code;
        $signupInvite = $inviteCode ? SignupInvitation::firstWhere(
            'invite_code', $inviteCode) : null;
        if (!$signupInvite) {
            return view('message', [
                'message' => 'Your sign-up invitation link is invalid.'
            ]);
        }
        if ($signupInvite->is_accepted) {
            return view('message', [
                'message' => 'Your sign-up invitation has already ' 
                    . 'been accepted.'
            ]);
        }
        if (now()->greaterThan($signupInvite->expires_at)) {
            return view('message', [
                'message' => 'Your sign-up invitation link has expired.'
            ]);
        }
        return $next($request);
    }
}
