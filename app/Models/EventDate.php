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
            ? Format::toLocal($this->start_time)->format('H:i')
            : null;
        return Attribute::make(
            get: fn () => $time,
        );
    }

    public function endTimeShort(): Attribute
    {
        $time = $this->end_time
            ? Format::toLocal($this->end_time)->format('H:i')
            : null;
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
            ? Format::toLocal($this->start_time)->format('g:i A')
            : null;
        return Attribute::make(
            get: fn () => $time
        );
    }

    public function endTimeFmt(): Attribute
    {
        $time = $this->end_time
            ? Format::toLocal($this->end_time)->format('g:i A')
            : null;
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

    #[Scope]
    protected function ongoing(Builder $query): void
    {
        $query->where(function ($query) {
            $query->where('date', Carbon::now()->toDateString())
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->whereColumn('start_time', '<=', 'end_time')
                            ->where(function ($query) {
                                $query->where('start_time', '<=',
                                    Carbon::now()->toTimeString())
                                    ->where('end_time', '>',
                                        Carbon::now()->toTimeString());
                            });
                    })->orWhere(function ($query) {
                        $query->whereColumn('start_time', '>', 'end_time')
                            ->where(function ($query) {
                                $query->where('start_time', '<=',
                                    Carbon::now()->toTimeString())
                                    ->orWhere('end_time', '>',
                                        Carbon::now()->toTimeString());
                            });
                    });
                });
        })->where(function ($query) {
            $query->whereHas('event.accomReport', function ($query) {
                $query->whereNotIn('status', ['pending', 'approved']);
            })->orDoesntHave('event.accomReport');
        });
    }

    #[Scope]
    protected function approved(Builder $query): void
    {
        $query->whereRelation('event.accomReport', 'status', 'approved');
    }
}
