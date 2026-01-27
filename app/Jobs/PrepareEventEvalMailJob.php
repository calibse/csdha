<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\EventDate;
use App\Jobs\SendEventEvalMailJob;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Carbon;
use App\Services\Format;

class PrepareEventEvalMailJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(public int $eventDateId)
    {
        //
    }

    public function handle(): void
    {
        $eventDate = EventDate::find($this->eventDateId);
        if (!$eventDate) return;
        $event = $eventDate->event;
        if (!$event->accept_evaluation) return;
        $date = $eventDate->end_date->setTimezone($event->timezone);
        $delayHours = $event->evaluation_delay_hours;
        $delayDate = $date->copy()->addHours($delayHours);
        $eventPassed = $date->copy()->diffInHours(now($event->timezone), 
            false) >= $delayHours;
        $jobs = [];
        // $eventPassed = true;
        foreach ($eventDate->attendees()->wherePivot('eval_mail_sent', 0)
                ->get() as $attendee) {
            $token = self::createToken();
            $url = route('events.evaluations.consent.edit', [
                'event' => $event->public_id,
                'token' => $token
            ]);
            $jobs[] = (new SendEventEvalMailJob($attendee->id, $eventDate->id, $url))
                ->delay($eventPassed ? 0 : $delayDate);
        }
        $batchName = "event_eval_mail_{$event->id}_{$eventDate->date}_" .
            "{$eventDate->start_date->toTimeString()}_{$eventDate->end_date->toTimeString()}";
        $batch = self::findBatch($batchName);
        if ($batch && $jobs) {
            $batch->add($jobs);
        } elseif ($jobs) {
            Bus::batch($jobs)->name($batchName)->dispatch();
        }
    }

    public function uniqueId(): string
    {
        return $this->eventDateId;
    }

    private static function createToken(): string
    {
        $token = Str::random(64);
        DB::table('event_evaluation_tokens')->insert([
            'token' => hash('sha256', $token),
            'created_at' => now(),
        ]);
        return $token;
    }

    private static function findBatch(string $name): ?Batch
    {
        $batchId = DB::table('job_batches')->where('name', $name)
            ->whereNull('cancelled_at')
            ->whereNull('finished_at')
            ->value('id');
        return $batchId ? Bus::findBatch($batchId) : null;
    }
}
