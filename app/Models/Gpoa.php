<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use WeasyPrint\Facade as WeasyPrint;
use App\Services\PagedView;
use App\Traits\HasPublicId;

class Gpoa extends Model
{
    use HasPublicId;

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
            'report_file_updated_at' => 'datetime',
        ];
    }

    public function accomReportViewData($startDate = null, 
        $endDate = null): array
    {
        $events = [];
        $eventQuery = $this->events()->approved($startDate, $endDate);
        $allEvents = $eventQuery->get();
        foreach ($allEvents as $event) {
            $events[] = $event->eventData();
        }
        $lastAccomReport = $eventQuery->reorder('dates_date', 'desc')->first()?->accomReport;
        return [
            'events' => $events,
            'editors' => User::withPerm('accomplishment-reports.edit')
                ->notOfPosition('adviser')->get(),
            'approved' => true,
            'president' => $lastAccomReport?->president,
        ];
    }

    public function events(): HasManyThrough
    {
        return $this->hasManyThrough(Event::class, GpoaActivity::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(GpoaActivity::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function closer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closer_user_id');
    }

    protected function hasApprovedAccomReport(): Attribute
    {
        $has = $this->events()->approved()->exists();
        return Attribute::make(
            get: fn () => $has
        );
    }

    protected function hasApprovedActivity(): Attribute
    {
        $has = $this->activities()->where('status', 'approved')->exists();
        return Attribute::make(
            get: fn () => $has
        );
    }

    protected function fullAcademicPeriod(): Attribute
    {
        $academicPeriod = $this->academicPeriod?->term?->label . ' A.Y. ' . 
            $this->academicPeriod?->year_label;
        return Attribute::make(
            get: fn () => $academicPeriod
        );
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function reportViewData()
    {
        $activities = $this->activities()->where('status', 'approved')
            ->orderBy('start_date', 'asc')->get();
        $lastActivity = $this->activities()->select(['id', 'president_user_id', 
            'adviser_user_id'])->where('status', 'approved')
            ->orderBy('adviser_approved_at', 'desc')->first();
        $president = $lastActivity->president;
        $adviser = $lastActivity->adviser;
        return [
            'gpoa' => $this,
            'activities' => $activities,
            'president' => $president,
            'adviser' => $adviser,
            'academicPeriod' => $this->academicPeriod,
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereNull('closed_at');
    }

    #[Scope]
    protected function closed(Builder $query): void
    {
        $query->whereNotNull('closed_at');
    }

    #[Scope]
    protected function withApprovedActivity(Builder $query): void
    {
        $query->whereRelation('activities', 'status', 'approved');
    }
}
