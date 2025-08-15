<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\HasPublicId;

class EventDate extends Model
{
    use HasPublicId;
    
    public function attendees(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function startTimeShort(): Attribute 
    {
        $time = $this->start_time 
            ? Carbon::parse($this->start_time)->tz(config('timezone'))->format('H:i')
            : null;
        return Attribute::make(
            get: fn () => $time,
        );
    }

    public function endTimeShort(): Attribute 
    {
        $time = $this->end_time 
            ? Carbon::parse($this->end_time)->tz(config('timezone'))->format('H:i')
            : null;
        return Attribute::make(
            get: fn () => $time,
        );
    }

    public function dateFmt(): Attribute 
    {
        $date = Carbon::parse($this->date)->tz(config('timezone'))->format('F j, Y');
        return Attribute::make(
            get: fn () => $date
        );
    }

    public function startTimeFmt(): Attribute
    {
        $time = $this->start_time 
            ? Carbon::parse($this->start_time)->tz(config('timezone'))->format('g:i A')
            : null;
        return Attribute::make(
            get: fn () => $time
        );
    }

    public function endTimeFmt(): Attribute
    {
        $time = $this->end_time 
            ? Carbon::parse($this->end_time)->tz(config('timezone'))->format('g:i A')
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
        date_default_timezone_set(config('timezone'));
        $query->where(function ($query) {
            $query->where('date', Carbon::now()->utc()->toDateString())
                ->where('start_time', null)->where('end_time', null);
        })->orWhere(function ($query) {
            $query->where('date', Carbon::now()->utc()->toDateString())
                ->where('start_time', '<=', Carbon::now()->utc()->toTimeString())
                ->where('end_time', '>=', Carbon::now()->utc()->toTimeString());
        });
    }
}
