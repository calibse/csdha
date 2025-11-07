<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Gpoa;
use App\Models\User;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\PagedView;

class MakeGpoaReport implements ShouldQueue
{
    use Queueable;

    public function __construct(public Gpoa $gpoa, public User $authUser)
    {
        //
    }

    public function handle(): void
    {
        $gpoa = $this->gpoa;
        if (!$gpoa && !$gpoa->activities()->where('status', 'approved')
            ->exists()) {
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
}
