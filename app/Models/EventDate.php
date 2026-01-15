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

    protected static function booted(): void
    {
        static::saving(function (EventDate $date) {
            $start = Carbon::parse("{$date->date} {$date->start_time}", 
                $event->timezone)->setTimezone('UTC');
            $end = Carbon::parse("{$date->date} {$date->end_time}", 
                $event->timezone)->setTimezone('UTC');
            $date->date = $start->toDateString();
            $date->start_time = $start->toTimeString();
            $date->end_time = $end->toTimeString();
        });
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

    public function startTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse(
                "{$this->date->toDateString()} {$this->start_time}")
                ->setTimezone($event->timezone)->toTimeString()
        );
    }

    public function endTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse(
                "{$this->date->toDateString()} {$this->end_time}")
                ->setTimezone($event->timezone)->toTimeString()
        );
    }

    public function date(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse(
                "{$this->date->toDateString()} {$this->start_time}")
                ->setTimezone($event->timezone)
        );
    }

    public function startTimeShort(): Attribute
    {
        $time = $this->start_time
            ? Carbon::parse($this->start_time)->format('H:i') : null;
        return Attribute::make(
            get: fn () => $time,
        );
    }

    public function endTimeShort(): Attribute
    {
        $time = $this->end_time
            ? Carbon::parse($this->end_time)->format('H:i') : null;
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
            ? Carbon::parse($this->start_time)->format('g:i A') : null;
        return Attribute::make(
            get: fn () => $time
        );
    }

    public function endTimeFmt(): Attribute
    {
        $time = $this->end_time
            ? Carbon::parse($this->end_time)->format('g:i A') : null;
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
        $start = "{$this->date->format('Y-m-d')} {$this->start_time}";
        $end = "{$this->date->format('Y-m-d')} {$this->end_time}";
        $ongoing = Carbon::now()->between($end, $start);
        return Attribute::make(
            get: fn () => $ongoing
        );
    }

    #[Scope]
    protected function upcoming(Builder $query): void
    {
        $query->join('events', 'events.id', '=', 'event_dates.event_id')
            ->select('event_dates.*')
            ->whereRaw('concat(date, start_time) > ?', [Carbon::now()])
            ->orderBy('date', 'asc')->orderBy('start_time', 'desc');
    }

    #[Scope]
    protected function ongoing(Builder $query): void
    {
        $query->join('events', 'events.id', '=', 'event_dates.event_id')
            ->select('event_dates.*')->where(function ($query) {
                $query->whereRaw('? between concat(date, start_time) and
                    concat(date, end_time)', [Carbon::now()])
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
