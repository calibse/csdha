<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DropAuditTriggers
{
    public static function run()
    {
        $dbName = 'csdha';
        if (config('database.default') === 'sqlite') {
            $stmts = DB::table('sqlite_master')->selectRaw(
                "concat('drop trigger if exists \"', name, '\"') as stmt"                
                )
                ->where('type', 'trigger')
                ->where('name', 'like', 'audit_%')
                ->pluck('stmt');
        } else {
            $stmts = DB::table('information_schema.triggers')->selectRaw(
                "concat('drop trigger if exists \"', trigger_name, '\"') as stmt"                
                )
                ->where('trigger_schema', $dbName)
                ->where('trigger_name', 'like', 'audit_%')
                ->pluck('stmt');
        }
        foreach ($stmts as $stmt) {
            DB::statement($stmt);
        }
    }    
}
