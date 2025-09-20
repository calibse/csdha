<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SignupInvitation;

class CheckSignupInviteCode
{
    public function handle(Request $request, Closure $next): Response
    {
        $inviteCode = session('inviteCode') ?? $request->invite_code;
        if (!$inviteCode) return $next($request);
        $signupInvite = $inviteCode ? SignupInvitation::firstWhere(
            'invite_code', $inviteCode) : null;
        if (!$signupInvite) {
            return response()->view('message', [
                'message' => 'Your sign-up invitation link is invalid.'
            ]);
        }
        if ($signupInvite->is_accepted) {
            return response()->view('message', [
                'message' => 'Your sign-up invitation has already '
                    . 'been accepted.'
            ]);
        }
        if (now()->greaterThan($signupInvite->expires_at)) {
            return response()->view('message', [
                'message' => 'Your sign-up invitation link has expired.'
            ]);
        }
        return $next($request);
    }
}
