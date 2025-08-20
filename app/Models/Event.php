<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use App\Services\PagedView;
use WeasyPrint\Facade as WeasyPrint;
use WeasyPrint\Objects\Config;
use App\Models\User;
use App\Traits\HasPublicId;

class Event extends Model
{
    use HasPublicId;
    
    public function accomReport(): HasOne
    {
        return $this->hasOne(AccomReport::class);
    }
    
    public function regisForm(): HasOne
    {
        return $this->hasOne(EventRegisForm::class);
    }

    public function evalForm(): HasOne
    {
        return $this->hasOne(EventEvalForm::class);
    }

    public function gpoaActivity(): BelongsTo
    {
        return $this->belongsTo(GpoaActivity::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fund(): HasOne
    {
        return $this->hasOne(Fund::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'event_attendances')
            ->as('attendance')
            ->withPivot(
                'course_id',
                'student_year_id',
                'student_section_id'
            )
            ->withTimestamps()
            ->using(EventAttendance::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(EventAttendance::class, 'event_id'); 
    }

    public function studentYears(): BelongsToMany
    {
        return $this->belongsToMany(StudentYear::class, 'event_attendances')
            ->as('attendance')
            ->withPivot(
                'course_id',
                'student_year_id',
                'student_section_id'
            )
            ->using(EventAttendance::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(StudentYear::class, 'event_participants');
    }

    public function attendeesByYear($year)
    {
        return $this->studentYears()->where('year', '=', $year)->get();
    }

    public function deliverables(): HasMany
    {
        return $this->hasMany(EventDeliverable::class);
    }

    public function editors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_editor');
    }

    public function gspoaEvent(): belongsTo
    {
        return $this->belongsTo(GSPOAEvent::class, 'gspoa_event_id');
    }


    public function canBeEditedBy(User $user)
    {
        if ($this->editors->contains($user)
            || $this->creator->is($user)
        ) return true;
            
        return false;
    }

    public function dates(): HasMany
    {
        return $this->hasMany(EventDate::class);
    }

    public function attendanceTest()
    {
        return $attendance;
    }

    public function accomReportFile()
    {
        switch ($this->participant_type) {
        case 'students':
            $attendance = [];
            $attendanceQuery = EventStudent::whereHas(
                'eventDate.event', function ($query) {
                    $query->whereKey($this->id);
            });
            $yearLevels = $this->participants;
            foreach ($yearLevels as $yearLevel) {
                $attendance[$yearLevel->label] = (clone $attendanceQuery)
                    ->whereBelongsTo($yearLevel, 'yearModel')->count();
            }
            $attendanceTotal = $attendanceQuery->whereHas('yearModel', 
                function ($query) use ($yearLevels) {
                    $query->whereIn('id', $yearLevels->pluck('id')->toArray());
            })->count();
            break;
        case 'officers':
            $attendance = User::whereHas('eventDates.event', function ($query) {
                $query->whereKey($this->id);
            })->get();
            $attendanceTotal = null;
            break;
        default:  
            $attendance = null;
            $attendanceTotal = null;
        }
        $viewData = [
            'event' => $this,
            'attendance' => $attendance,
            'attendanceTotal' => $attendanceTotal,
            'editors' => User::withPerm('accomplishment-reports.edit')->get(),
            'activity' => $this->gpoaActivity,
            'approved' => $this->accomReport?->status === 'approved',
            'president' => User::ofPosition('president')->first()
        ];
        $format = 'pdf';
        return match ($format) {
            'html' => view('events.accom-report', $viewData),
            'pdf' => WeasyPrint::prepareSource(
                new PagedView('events.accom-report', $viewData))
                ->stream('accom_report.pdf')
        };
    }

    public function compactDates()
    {
        $dates = $this->dates()
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
        $newDates = [];
        for ($i = 0; $i < count($dates); ++$i) {
            if ($dates[$i]->start_time) { 
                $fullDate = $dates[$i]->date_fmt . ' | ' . 
                    $dates[$i]->start_time_fmt . ' - ' . $dates[$i]->end_time_fmt;
            }
            else {
                $fullDate = $dates[$i]->date_fmt; 
            }
            while ($i + 1 < count($dates) && $dates[$i]->start_time 
                && $dates[$i]->date === $dates[$i + 1]->date) {
                ++$i;
                if ($i + 1 < count($dates) && $dates[$i]->date === 
                        $dates[$i + 1]->date) {
                    $fullDate .= ', ' . $dates[$i]->start_time_fmt . ' - ' . 
                        $dates[$i]->end_time_fmt;
                }
                else {
                    $fullDate .= ' and ' . $dates[$i]->start_time_fmt . ' - ' . 
                        $dates[$i]->end_time_fmt;
                    break;
                }
            }
            $newDates[] = $fullDate;
        }

        return $newDates;
    }

    public function isUpcoming(): Attribute 
    {
        date_default_timezone_set(config('timezone'));
        $isUpcoming = $this->dates()->where(function ($query) {
            $query->whereRaw('? between date_sub(date, interval 5 day) and date'
                , [Carbon::today()->utc()]);
        })->exists();
        return Attribute::make(
            get: fn () => $isUpcoming,
        );
    }

    public function isOngoing(): Attribute 
    {
        date_default_timezone_set(config('timezone'));
        $isOngoing = $this->dates()->where(function ($query) {
            $query->where('date', Carbon::now()->utc()->toDateString())
                ->where('start_time', null)->where('end_time', null);
        })->orWhere(function ($query) {
            $query->where('date', Carbon::now()->utc()->toDateString())
                ->where('start_time', '<=', Carbon::now()->utc()->toTimeString())
                ->where('end_time', '>=', Carbon::now()->utc()->toTimeString());
        })->exists();
        return Attribute::make(
            get: fn () => $isOngoing,
        );
    }

    public function attendees(): Attribute
    {
        $attendees = EventStudent::whereHas('eventDate.event', 
            function ($query) {
                $query->whereKey($this->id);
            })->get(); 
        return Attribute::make(
            get: fn () => $attendees
        );
    }

}
