<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;
use App\Models\Gpoa;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Cache;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\PagedView;
use Throwable;

class GenerateAccomReport implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    private const JOB_CACHE = 'gen_accom_reports';

    public function __construct(public Gpoa $gpoa, public User $user, 
        public string $requestId, public string $startDate, 
        public string $endDate)
    {
    }

    public function handle(): void
    {
        $user = $this->user;
        $jobs = Cache::get(self::JOB_CACHE, []);
        $userJob = $jobs[$user->id] ?? [];
        $gpoa = $this->gpoa;
        $gpoaActive = $gpoa?->active;
        $currentRequest = $userJob ? $userJob['request_id'] === 
            $this->requestId : false;
        if (!$user || !$gpoaActive || !$currentRequest) return;
        $file = "gen_accom_reports/accom_report_{$user->id}.pdf";
        WeasyPrint::prepareSource(new PagedView('events.accom-report',
            $gpoa->accomReportViewData($this->startDate, $this->endDate)))
            ->putFile($file);
        Cache::lock(self::JOB_CACHE . '_lock', 2)->block(1, function () {
            $jobs = Cache::get(self::JOB_CACHE, []);
            $userJob = $jobs[$this->user->id] ?? [];
            $currentRequest = $userJob ? $userJob['request_id'] === 
                $this->requestId : false;
            if (!$currentRequest) return;
            $userJob['finished'] = true;
            $jobs[$this->user->id] = $userJob;
            Cache::put(self::JOB_CACHE, $jobs);
        });
    }

    public function uniqueId(): string
    {
        return $this->requestId;
    }

    public function failed(?Throwable $exception): void
    {
        Cache::lock(self::JOB_CACHE . '_lock', 2)->block(1, function () {
            $jobs = Cache::get(self::JOB_CACHE, []);
            unset($jobs[$this->user->id]);
            Cache::put(self::JOB_CACHE, $jobs);
        });
    }
}
