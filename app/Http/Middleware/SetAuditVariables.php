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
        DB::statement("
            create temporary table audit_trail_data (
                `action` varchar(10) DEFAULT NULL,
                `table_name` varchar(100) DEFAULT NULL,
                `column_names` longtext DEFAULT NULL,
                `primary_key` bigint(20) DEFAULT NULL,
                `request_id` char(26) DEFAULT NULL,
                `request_ip` varchar(45) DEFAULT NULL,
                `request_url` text DEFAULT NULL,
                `request_method` varchar(10) DEFAULT NULL,
                `request_time` timestamp NULL DEFAULT NULL,
                `user_id` bigint(20) DEFAULT NULL,
                `user_agent` text DEFAULT NULL,
                `session_id` varchar(255) DEFAULT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
            )
        ");
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
/*
        $variables = [
        ];
        foreach ($variables as $key => $value) {
            $safe = $value === null ? 'NULL' : "'" . addslashes($value) . "'";
            DB::unprepared("SET @{$prefix}{$key} = {$safe}");
        }
*/
        return $next($request);
    }
}
