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
            "{$this->start_time}", $timezone)->setTimezone(config('timezone'));
        $end = Carbon::parse("{$this->date->format('Y-m-d')} " .
            "{$this->end_time}", $timezone)->setTimezone(config('timezone'));
        $ongoing = Carbon::now(config('timezone'))->between($end, $start);
        return Attribute::make(
            get: fn () => $ongoing
        );
    }

    #[Scope]
    protected function ongoing(Builder $query): void
    {
        $timezone = config('timezone');
        $query->whereRaw('convert_tz(now(), @@session.time_zone, ?) between
            convert_tz(timestamp(date, start_time), timezone, ?) and
            convert_tz(timestamp(date, end_time), timezone, ?)',
            [$timezone, $timezone, $timezone])
            ->join('events', 'events.id', '=', 'event_dates.event_id')
            ->select('event_dates.*')
            ->where(function ($query) {
                $query->whereHas('event.accomReport', function ($query) {
                    $query->whereNotIn('status', ['pending', 'approved']);
                })->orDoesntHave('event.accomReport');
            });

/*
        $timezone = config('timezone');
        $date = "date(convert_tz(date, timezone, '{$timezone}'))";
        $startTime = "time(convert_tz(start_time, timezone, '{$timezone}'))";
        $endTime = "time(convert_tz(end_time, timezone, '{$timezone}'))";
        $now = Carbon::now(config('timezone'));
        $timeQuery = function ($query) use ($now, $startTime, $endTime) {
            $query->where(function ($query) use ($now, $startTime, $endTime) {
                $query->whereColumn(DB::raw($startTime), '<=',
                        DB::raw($endTime))
                    ->where(function ($query) use ($now, $startTime, $endTime) {
                        $query->whereRaw($startTime . ' <= ?',
                                [$now->toTimeString()])
                            ->whereRaw($endTime . ' > ?',
                                [$now->toTimeString()]);
                    });
            })->orWhere(function ($query) use ($now, $startTime, $endTime) {
                $query->whereColumn(DB::raw($startTime), '>',
                        DB::raw($endTime))
                    ->where(function ($query) use ($now, $startTime, $endTime) {
                        $query->whereRaw($startTime . ' <= ?',
                                [$now->toTimeString()])
                            ->orWhereRaw($endTime . ' > ?',
                                [$now->toTimeString()]);
                    });
            });
        };
        $dateQuery = function ($query) use ($now, $date, $timeQuery) {
            $query->whereRaw($date . ' = ?', [$now->toDateString()])
                ->where($timeQuery);
        };
        $query->join('events', 'events.id', '=', 'event_dates.event_id')
            ->select('event_dates.*')
            ->where($dateQuery)->where(function ($query) {
                $query->whereHas('event.accomReport', function ($query) {
                    $query->whereNotIn('status', ['pending', 'approved']);
                })->orDoesntHave('event.accomReport');
            });
*/
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
