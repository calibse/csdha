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

    public function __construct(public EventDate $eventDate)
    {
        //
    }

    public function handle(): void
    {
        $event = $eventDate->event;
        if (!$event->accept_evaluation) return;
        $batchName = "event_eval_mail_{$event->id}_{$eventDate->date}";
        $batch = self::findBatch($batchName);
        $batch?->cancel();
        $date = Carbon::createFromFormat('Y-m-d H:i:s',
            "$eventDate->date $eventDate->end_time", $event->timezone);
        $delayHours = 24;
        $delayDate = Carbon::now($event->timezone)->addHours($delayHours);
        $eventPassed = now($event->timezone)->diffHours($date) >=
            $delayHours;
        $jobs = [];
        foreach ($eventDate->attendees()->where('eval_mail_sent', 0)->get()
                as $attendee) {
            $token = self::createToken();
            $url = route('events.evaluations.consent.edit', [
                'event' => $event->public_id,
                'token' => $token
            ]);
            $jobs[] = (new SendEventEvalMailJob($attendee, $event, $url))
                ->delay($eventPassed ? 0 : $delayDate);
        }
        Bus::batch($jobs)->name($batchName)->dispatch();
    }

    private static function createToken(): string
    {
        $token = Str::random(64);
        DB::table('event_evaluation_tokens')->insert([
            'token' => Hash::make($token),
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
