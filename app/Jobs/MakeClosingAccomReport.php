<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Models\Event;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\PagedView;
use App\Models\User;
use App\Models\Gpoa;

class MakeClosingAccomReport implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(public Gpoa $gpoa)
    {
        //
    }

    public function handle(): void
    {
        $gpoa = $this->gpoa;
        $hasApproved = $gpoa->has_approved_accom_report;
        if (!$gpoa || !$hasApproved) return;
        $file = "gpoas/gpoa_{$gpoa->id}/accom_report.pdf";
        WeasyPrint::prepareSource(new PagedView('events.accom-report',
            $gpoa->accomReportViewData()))->putFile($file);
        $gpoa->accom_report_filepath = $file;
        $gpoa->save();
    }

    public function uniqueId(): string
    {
        return $this->gpoa->id; 
    }
}
