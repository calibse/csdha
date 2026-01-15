<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') === 'sqlite') {
            $sql = DB::table('sqlite_master')->where('type', 'table')
                ->where('name', 'audit_trail')->value('sql');
            $newConstraint = <<<SQL
constraint "chk_request_method" 
check("request_method" in ('GET','POST','PUT','PATCH','DELETE','OPTIONS'))

SQL;
            $oldTable = 'audit_trail';
            $newTable = 'audit_trail_copy';
            $sql = preg_replace('/^CREATE\s+TABLE\s+"?' 
                . preg_quote($oldTable, '/') . '"?\s*\(/i', 
                'CREATE TABLE "' . $newTable . '"(', $sql);
            $sql = preg_replace('/\s*,?\s*CONSTRAINT\s+"chk_request_method"\s+CHECK\s*\([^)]*\)/i', 
                $newConstraint, $sql);
            DB::unprepared($sql);
            $sql = <<<SQL
insert into "audit_trail_copy"
select * from "audit_trail";
drop table "audit_trail";
alter table "audit_trail_copy" 
rename to "audit_trail";

SQL;
            DB::unprepared($sql);
        } else {
            $sql = <<<SQL
alter table "audit_trail"
drop constraint "chk_request_method";
alter table "audit_trail"
add constraint "chk_request_method"
check("request_method" in ('GET','POST','PUT','PATCH','DELETE','OPTIONS'));
SQL;
            DB::unprepared($sql);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
