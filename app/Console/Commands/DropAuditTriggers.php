<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DropAuditTriggers as DropAllAuditTriggers;

class DropAuditTriggers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:drop-audit-triggers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop audit triggers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DropAllAuditTriggers::run();
    }
}
