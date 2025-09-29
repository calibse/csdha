<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Traits\HasPublicId;

class Event extends Model
{
    use HasPublicId;

    public function gpoa()
    {
        return $this->gpoaActivity->gpoa;
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(EventEvaluation::class);
    }

    public function attachmentSets(): HasMany
    {
        return $this->hasMany(EventAttachmentSet::class);
    }

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

    public function allMembersAttend() {
        $members = [
            'BSIT' => ['1', '2', '3', '4'],
            'DIT' => ['1', '2']
        ];
        foreach ($members as $program => $years) {
            foreach ($years as $year) {
                $exists = EventStudent::whereHas(
                    'eventDates.event', function ($query) {
                        $query->whereKey($this->id);
                    })->whereRelation('yearModel', 'year', $year)
                    ->whereRelation('course', 'acronym', $program)->exists();
                if (!$exists) return false;
            }
        }
        return true;
    }

    public function accomReportViewData()
    {
        $members = [
            'BSIT' => ['1', '2', '3', '4'],
            'DIT' => ['1', '2']
        ];
        $attendance = collect();
        $attendanceTotal = null;
        $attendanceView = null;
        switch ($this->participant_type) {
        case 'students':
            $attendanceQuery = EventStudent::whereHas(
                'eventDates.event', function ($query) {
                    $query->whereKey($this->id);
            });
            $yearLevels = $this->participants;
            $attendeesListQuery = (clone $attendanceQuery)
                ->whereHas('yearModel', function ($query) use ($yearLevels) {
                    $query->whereIn('id', $yearLevels->pluck('id')->toArray());
            });
            $attendanceTotal = (clone $attendeesListQuery)->count();
            if ($attendanceTotal <= 15) {
                $attendanceView = 'student';
                $attendance = (clone $attendeesListQuery)
                    ->orderBy('last_name', 'asc')->get();
            } elseif ($this->allMembersAttend()) {
                $attendanceView = 'year';
                foreach ($yearLevels as $yearLevel) {
                    $attendance[$yearLevel->label] = (clone $attendanceQuery)
                        ->whereBelongsTo($yearLevel, 'yearModel')->count();
                }
            } else {
                $attendanceView = 'program';
                foreach ($members as $program => $years) {
                    foreach ($years as $year) {
                        $count = EventStudent::whereHas(
                            'eventDates.event', function ($query) {
                                $query->whereKey($this->id);
                            })->whereRelation('yearModel', 'year', $year)
                            ->whereRelation('course', 'acronym', $program)
                            ->count();
                        if (!$count) continue;
                        $progYear = "{$program} {$year}";
                        $attendance[$progYear] = $count;
                    }
                }
            }
            break;
        case 'officers':
            $attendance = $this->officerAttendees();
            break;
        }
        return [
            'event' => $this,
            'attendance' => $attendance,
            'attendanceTotal' => $attendanceTotal,
            'attendanceView' => $attendanceView,
            'activity' => $this->gpoaActivity,
            'comments' => $this->comments(),
        ];
    }

    public function comments()
    {
        $subQuery = $this->evaluations()
                ->select('topics_covered as comment')
                ->where('feature_topics_covered', 1);
        $commentQueries = [
            $this->evaluations()
                ->select('suggestions_for_improvement as comment')
                ->where('feature_suggestions_for_improvement', 1),
            $this->evaluations()
                ->select('future_topics as comment')
                ->where('feature_future_topics', 1),
            $this->evaluations()
                ->select('overall_experience as comment')
                ->where('feature_overall_experience', 1),
            $this->evaluations()
                ->select('additional_comments as comment')
                ->where('feature_additional_comments', 1)
        ];
        foreach ($commentQueries as $commentQuery) {
            $subQuery->unionAll($commentQuery);
        }
        return DB::query()->fromSub($subQuery, 'comment')
            ->orderByRaw('length(comment) desc')->pluck('comment');
    }

    public function compactDates()
    {
        $dates = $this->dates()->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')->get();
        $newDates = [];
        $dateCount = count($dates);
        for ($i = 0; $i < $dateCount; ++$i) {
            $fullDate = $dates[$i]->date_fmt . ' | ' .
                $dates[$i]->start_time_fmt . ' - ' . $dates[$i]->end_time_fmt;
            while ($i + 1 < $dateCount && $dates[$i]->date->toDateString()
                    === $dates[$i + 1]->date->toDateString()) {
                ++$i;
                if ($i + 1 < $dateCount) {
                    $fullDate .= ', ' . $dates[$i]->start_time_fmt . ' - ' .
                        $dates[$i]->end_time_fmt;
                } else {
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

    public function officerAttendees()
    {
        $attendees = User::withAggregate('position', 'position_order')
            ->whereHas('eventDates.event', function ($query) {
                $query->whereKey($this->id);
            })->orderBy('position_position_order', 'asc')->get();
        return $attendees;
    }

    public function attendees()
    {
        $attendees = EventStudent::whereHas('eventDates.event', function ($query) {
            $query->whereKey($this->id);
        })->get();
        return $attendees;
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereHas('gpoaActivity.gpoa', function ($query) {
            $query->where('active', 1);
        });
    }

    #[Scope]
    protected function approved(Builder $query, $startDate = null,
            $endDate = null): void
    {
        $query->withAggregate('dates', 'date')
            ->whereRelation('accomReport', 'status', 'approved');
        if ($startDate && $endDate) {
            $query->whereHas('dates', function ($query)
                    use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            });
        }
        $query->orderBy('dates_date', 'asc');
    }

}
