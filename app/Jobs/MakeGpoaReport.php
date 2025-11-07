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

class MakeGpoaReport implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(public Gpoa $gpoa, public User $authUser,
        public ?GpoaActivity $activity = null)
    {
        //
    }

    public function handle(): void
    {
        $gpoa = $this->gpoa;
        $gpoaActive = $gpoa?->active;
        if (!$this->authUser || !$gpoaActive || !$gpoa?->activities()
            ->where('status', 'approved')->exists()) {
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
        return $this->gpoa->id . '_' . $this->authUser->id . '_' . 
            $this->activity?->id;
    }
}
