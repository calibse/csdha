<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Gpoa;
use App\Models\User;
use App\Models\GpoaActivity;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\PagedView;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MakeClosingGpoaReport implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(public Gpoa $gpoa, public User $authUser)
    {
        //
    }

    public function handle(): void
    {
        $gpoa = $this->gpoa;
        $hasApproved = $gpoa->has_approved_activity;
        if (!$this->authUser || !$gpoa || !$hasApproved) {
            return;
        }
        $gpoa->report_file_updated = false;
        $gpoa->save();
        $reportFile = "gpoas/gpoa_{$gpoa->id}/gpoa_report.pdf";
        WeasyPrint::prepareSource(new PagedView('gpoa.report',
            $gpoa->reportViewData() + [
                'authUser' => $this->authUser
            ]))->putFile($reportFile);
        $gpoa->report_filepath = $reportFile;
        $gpoa->report_file_updated = true;
        $gpoa->report_file_updated_at = now();
        $gpoa->save();
    }

    public function uniqueId(): string 
    {
        return $this->gpoa->id;
    }
}
