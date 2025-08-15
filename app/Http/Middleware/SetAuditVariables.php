<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SetAuditVariables
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $prefix = 'audit_';
        $requestId = (string) Str::ulid();
        $variables = [
            'request_id' => $requestId,
            'request_ip' => $request->ip(),
            'request_url' => $request->fullUrl(),
            'request_method' => $request->method(),
            'request_time' => now()->toDateTimeString(),
            'user_id' => auth()->id(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId()
        ];
        foreach ($variables as $key => $value) {
            $safe = $value === null ? 'NULL' : "'" . addslashes($value) . "'";
            DB::unprepared("SET @{$prefix}{$key} = {$safe}");
        }
        return $next($request);
    }
}
