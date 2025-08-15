<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GenerateAuditTriggers;

class InstallAuditTriggers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:audit-triggers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install audit triggers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        GenerateAuditTriggers::run();
    }
}
