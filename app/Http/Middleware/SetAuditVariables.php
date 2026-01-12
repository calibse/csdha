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
        DB::statement('
            create temporary "table audit_trail_data" (
                "action" varchar(10) default null,
                "table_name" varchar(100) default null,
                "column_names" longtext default null,
                "primary_key" bigint(20) default null,
                "request_id" char(26) default null,
                "request_ip" varchar(45) default null,
                "request_url" text default null,
                "request_method" varchar(10) default null,
                "request_time" timestamp null default null,
                "user_id" bigint(20) default null,
                "user_agent" text default null,
                "session_id" varchar(255) default null,
                "created_at" timestamp null default null,
            )
        ');
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
