<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class GenerateAuditTriggers
{
    private static string $dbName;
    private static string $prefix; 
    private static string $tableName;
    private static array $excludes;

    private static function boot()
    {
        self::$dbName = DB::getDatabaseName();
        self::$prefix = 'audit_';
        self::$tableName = 'audit_trail';
        self::$excludes = [self::$tableName, 'migrations', 'sessions', 
            'personal_access_tokens', 'event_evaluation_tokens', 
            'password_reset_tokens', 'cache', 'cache_locks', 'jobs', 
            'job_batches', 'failed_jobs', 'audit_trail_data', 
            'audit_trigger_variables', 'audit_trail_data_copy'];
    }

    public static function run()
    {
        self::boot();
        self::dropAllTriggers();
        self::createTriggers();
    }

    private static function dropAllTriggers()
    {
        if (config('database.default') === 'sqlite') {
            $triggers = DB::table('sqlite_master')->where('type', 'trigger')
                ->pluck('name');
        } else {
            $triggers = DB::table('information_schema.triggers')
                ->where('trigger_schema', self::$dbName)->pluck('trigger_name');
        }
        foreach ($triggers as $trigger) {
            if (str_starts_with($trigger, self::$prefix)) {
                DB::statement("drop trigger if exists \"{$trigger}\"");
            }
        }
    }

    private static function createTriggers()
    {
        if (config('database.default') === 'sqlite') {
            $columnCompOperator = 'is';
        } else {
            $columnCompOperator = '<=>';
        }
        if (config('database.default') === 'sqlite') {
            $tables = DB::table('sqlite_master')->where('type', 'table')
                ->where('name', 'not like', 'sqlite_%')
                ->pluck('name');
        } else {
            $tables = DB::table('information_schema.tables')
                ->where('table_schema', self::$dbName)->pluck('table_name');
        }
        foreach ($tables as $table) {
            $auditTable = self::$tableName;
            $tableName = $table;
            if (in_array($tableName, self::$excludes)) {
                continue;
            }
            if (config('database.default') === 'sqlite') {
                $columns = collect(DB::select('
                    select "name"
                    from pragma_table_info(?)
                ', [$tableName]))->pluck('name');
            } else {
                $columns = DB::table('information_schema.columns')
                    ->where('table_schema', self::$dbName)
                    ->where('table_name', $tableName)->pluck('column_name');
            }
            $colNames = $columns;
            foreach (['insert', 'update', 'delete'] as $action) {
                $triggerName = self::$prefix . "{$tableName}_{$action}";
                $timing = 'after';
                $when = $action;
                $columnDiff = '';
                if ($action === 'update') {
                    $colCount = count($colNames);
                    $changedCol = 'concat(';
                    for ($i = 0; $i < $colCount; $i++) {
                        $changedCol .= <<<SQL
    case when not (old."$colNames[$i]" = new."$colNames[$i]" 
      or (old."$colNames[$i]" is null and new."$colNames[$i]" is null))
    then '"$colNames[$i]",'
    else ''
    end
SQL;
                        if ($i < $colCount - 1) {
                            $changedCol .= ', ';
                        }
                    }
                    $changedCol .= ')';
                    $setChangedCols = <<<SQL
insert into "audit_trigger_variables" ("changed_cols") 
values ($changedCol);
SQL;
                    $changedCol = <<<SQL
(select concat('[', substr("changed_cols", 1, length("changed_cols") -1), ']')
from "audit_trigger_variables")
SQL;
                } else {
                    $setChangedCols = '';
                    $changedCol = "'[]'";
                }
                $columnsChangedValue = match ($action) {
                    'insert', 'delete' => 'NULL',
                    'update' => 'changed_cols',
                };
                $primaryKeyValue = match ($action) {
                    'delete', 'update' => 'old.id',
                    'insert' => 'new.id',
                };
                $prepareAuditData = <<<SQL
$setChangedCols
update "audit_trail_data" set
action = '$action',
table_name = '$tableName',
primary_key = $primaryKeyValue,
created_at = current_timestamp,
column_names = ($changedCol) ;

update "audit_trail_data_copy" set
action = '$action',
table_name = '$tableName',
primary_key = $primaryKeyValue,
created_at = current_timestamp,
column_names = $changedCol ;

SQL;
                $sql = <<<SQL
create trigger "$triggerName"
$timing $when on "$tableName"
for each row
begin
  $prepareAuditData
  insert into "$auditTable" (
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  )
  select
    "action",
    "table_name",
    "column_names",
    "primary_key",
    "request_id",
    "request_ip",
    "request_url",
    "request_method",
    "request_time",
    "user_id",
    "user_agent",
    "session_id",
    "created_at"
  from "audit_trail_data";
  delete from "audit_trail_data";
  delete from "audit_trigger_variables";
end;

SQL;
                DB::unprepared($sql);
            }
        }
    }
}
