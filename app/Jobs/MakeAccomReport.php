<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Models\Event;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\PagedView;

class MakeAccomReport implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(public Event $event)
    {
        //
    }

    public function handle(): void
    {
        $event = $this->event;
        $accomReport = $event->accomReport;
        if (!$event || !$accomReport) return;
        $accomReport->file_updated = false;
        $accomReport->save();
        $file = "accom_reports/accom_report_{$event->id}.pdf";
        WeasyPrint::prepareSource(new PagedView(
            'events.accom-report', $event->accomReportViewData()))
            ->putFile($file);
        $accomReport->filepath = $file;
        $accomReport->file_updated = true;
        $accomReport->file_updated_at = now();
        $accomReport->save();
    }
}
