<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasPublicId;
use App\Services\Format;
use Illuminate\Support\Facades\DB;

class EventDate extends Model
{
    use HasPublicId;

    protected function casts(): array
    {
        return [
            'date' => 'date'
        ];
    }

    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(EventStudent::class, 'event_attendees')
            ->as('eventAttendee')->withPivot('created_at', 'eval_mail_sent')
            ->withTimestamps();
    }

    public function officerAttendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_officer_attendees')
            ->withPivot('created_at')->withTimestamps();
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function hasAttendees(): Attribute
    {
        $has = false;
        switch ($this->event->participant_type) {
        case 'students':
            $has = $this->attendees()->exists();
            break;
        case 'officers':
            $has = $this->officerAttendees()->exists();
            break;
        }
        return Attribute::make(
            get: fn () => $has,
        );
    }

    public function startTimeShort(): Attribute
    {
        $time = $this->start_time
            ? Carbon::parse($this->start_time, $this->event->timezone)->format('H:i') : null;
        return Attribute::make(
            get: fn () => $time,
        );
    }

    public function endTimeShort(): Attribute
    {
        $time = $this->end_time
            ? Carbon::parse($this->end_time, $this->event->timezone)->format('H:i') : null;
        return Attribute::make(
            get: fn () => $time,
        );
    }

    public function dateFmt(): Attribute
    {
        $date = $this->date->format('F j, Y');
        return Attribute::make(
            get: fn () => $date
        );
    }

    public function startTimeFmt(): Attribute
    {
        $time = $this->start_time
            ? Carbon::parse($this->start_time, $this->event->timezone)->format('g:i A') : null;
        return Attribute::make(
            get: fn () => $time
        );
    }

    public function endTimeFmt(): Attribute
    {
        $time = $this->end_time
            ? Carbon::parse($this->end_time, $this->event->timezone)->format('g:i A') : null;
        return Attribute::make(
            get: fn () => $time
        );
    }

    public function fullDate(): Attribute
    {
        $date = $this->date_fmt . ($this->start_time ? ' '
            . $this->start_time_fmt : null) . ($this->end_time_fmt ? ' - '
            . $this->end_time_fmt : null);
        return Attribute::make(
            get: fn () => $date
        );
    }

    public function fullTime(): Attribute
    {
        $date = ($this->start_time ? ' '
            . $this->start_time_fmt : null) . ($this->end_time_fmt ? ' - '
            . $this->end_time_fmt : null);
        return Attribute::make(
            get: fn () => $date
        );
    }

    public function isOngoing(): Attribute
    {
        $timezone = $this->event->timezone;
        $start = Carbon::parse("{$this->date->format('Y-m-d')} " .
            "{$this->start_time}", $timezone);
        $end = Carbon::parse("{$this->date->format('Y-m-d')} " .
            "{$this->end_time}", $timezone);
        $ongoing = Carbon::now($timezone)->between($end, $start);
        return Attribute::make(
            get: fn () => $ongoing
        );
    }

    #[Scope]
    protected function upcoming(Builder $query): void
    {
        $query->join('events', 'events.id', '=', 'event_dates.event_id')
            ->select('event_dates.*')
            ->whereRaw("timestamp(date, start_time) >
                convert_tz(now(), @@session.time_zone, timezone)")
            ->orderBy('date', 'asc')->orderBy('start_time', 'desc');
    }

    #[Scope]
    protected function ongoing(Builder $query): void
    {
        $query->join('events', 'events.id', '=', 'event_dates.event_id')
            ->select('event_dates.*')->where(function ($query) {
                $query->whereRaw('convert_tz(now(), @@session.time_zone, 
                    timezone) between timestamp(date, start_time) and
                    timestamp(date, end_time)')
                ->where(function ($query) {
                    $query->whereHas('event.accomReport', function ($query) {
                        $query->whereNotIn('status', ['pending', 'approved']);
                    })->orDoesntHave('event.accomReport');
                });
        });
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereHas('event.gpoaActivity.gpoa', function ($query) {
            $query->where('active', 1);
        });
    }

    #[Scope]
    protected function approved(Builder $query): void
    {
        $query->whereRelation('event.accomReport', 'status', 'approved');
    }
}
