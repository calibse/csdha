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
        self::$excludes = [self::$tableName, 'migrations', 
            'password_reset_tokens', 'sessions', 'cache', 'cache_locks', 
            'jobs', 'job_batches', 'failed_jobs'];
    }

    public static function run()
    {
        self::boot();
        self::dropAllTriggers();
        self::createTriggers();
    }

    private static function dropAllTriggers()
    {
        $triggers = DB::select("
            select trigger_name 
            from information_schema.triggers 
            where trigger_schema = ?", [self::$dbName]);
        foreach ($triggers as $trigger) {
            if (str_starts_with($trigger->trigger_name, self::$prefix)) {
                DB::unprepared("
                    drop trigger if exists
                    `{$trigger->trigger_name}`;");
            }
        }
    }

    private static function createTriggers()
    {
        $tables = DB::select("
            select table_name 
            from information_schema.tables 
            where table_schema = ?", [self::$dbName]);
        foreach ($tables as $table) {
            $auditTable = self::$tableName;
            $tableName = $table->table_name;
            if (in_array($tableName, self::$excludes)) {
                continue;
            }
            $columns = DB::select("
                select column_name 
                from information_schema.columns 
                where table_schema = ? and table_name = ?", 
                [self::$dbName, $tableName]);
            $colNames = array_map(fn($col) => $col->column_name, $columns);
            foreach (['insert', 'update', 'delete'] as $action) {
                $triggerName = self::$prefix . "{$tableName}_{$action}";
                $timing = "after";
                $when = strtoupper($action);
                $columnDiff = '';
                if ($action === 'update') {
                    $columnDiff .= "
                        declare changed_cols json;\n
                        set changed_cols = json_array();\n";
                    foreach ($colNames as $col) {
                        $columnDiff .= <<<SQL
if not old.`$col` <=> new.`$col` then
  set changed_cols = json_array_append(changed_cols, '$', '$col');
end if;
SQL;
                        $columnDiff .= "\n";
                    }
                }
                $columnsChangedValue = match ($action) {
                    'insert', 'delete' => 'NULL',
                    'update' => 'changed_cols',
                };
                $primaryKeyValue = match ($action) {
                    'delete', 'update' => 'old.id',
                    'insert' => 'new.id',
                };
                $sql = <<<SQL
create trigger `$triggerName`
$timing $when on `$tableName`
for each row
begin
  $columnDiff

  insert into $auditTable (
    action,
    table_name,
    column_names,
    primary_key,
    request_id,
    request_ip,
    request_url,
    request_method,
    request_time,
    user_id,
    user_agent,
    session_id,
    created_at
  )
  values (
    '$action',
    '$tableName',
    $columnsChangedValue,
    $primaryKeyValue,
    coalesce(@audit_request_id, null),
    coalesce(@audit_request_ip, null),
    coalesce(@audit_request_url, null),
    coalesce(@audit_request_method, null),
    coalesce(@audit_request_time, null),
    coalesce(@audit_user_id, null),
    coalesce(@audit_user_agent, null),
    coalesce(@audit_session_id, null),
    now()
  );
end;
SQL;
                DB::unprepared($sql);
            }
        }
    }
}
