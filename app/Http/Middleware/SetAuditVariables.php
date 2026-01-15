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
        if (!in_array($request->method(), ['PUT', 'POST', 'DELETE'])) {
            return $next($request);
        }
        DB::table('audit_trail_data_copy')->insert([
            'request_id' => $requestId,
            'request_ip' => $request->ip(),
            'request_url' => $request->fullUrl(),
            'request_method' => $request->method(),
            'request_time' => now()->toDateTimeString(),
            'user_id' => auth()->id(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId()
        ]);
        DB::table('audit_trail_data')->delete();
        DB::table('audit_trail_data')->insert([
            'request_id' => $requestId,
            'request_ip' => $request->ip(),
            'request_url' => $request->fullUrl(),
            'request_method' => $request->method(),
            'request_time' => now()->toDateTimeString(),
            'user_id' => auth()->id(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId()
        ]);
        return $next($request);
/*
        $variables = [
        ];
        foreach ($variables as $key => $value) {
            $safe = $value === null ? 'NULL' : "'" . addslashes($value) . "'";
            DB::unprepared("SET @{$prefix}{$key} = {$safe}");
        }
*/
    }
}
