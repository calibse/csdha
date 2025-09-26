<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CheckEventEvalForm
{
    public function handle(Request $request, Closure $next): Response
    {
        $rawToken = $request->token;
        $response = response()->view('message', [
            'message' => 'Your event evaluation link is invalid.'
        ]);
        if (!$rawToken) return $response;
        $tokenExists = DB::table('event_evaluation_tokens')
            ->where('token', hash('sha256', $rawToken))->exists();
        if (!$tokenExists) return $response;
        return $next($request);
    }
}
