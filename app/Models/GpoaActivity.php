<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Traits\HasPublicId;
use App\Services\Format;
use App\Events\EventUpdated;
use App\Events\EventDatesChanged;

class GpoaActivity extends Model
{
    use HasPublicId;

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'president_submitted_at' => 'datetime',
            'officers_submitted_at' => 'datetime',
            'president_returned_at' => 'datetime',
            'adviser_returned_at' => 'datetime',
            'rejected_at' => 'datetime',
            'adviser_approved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (GpoaActivity $activity) {
            if ($activity->eventHeads->isEmpty()) {
                $activity->eventHeads()->attach(auth()->user(),
                    ['role' => 'event head']);
                $activity->save();
            }
        });
    }

    public function gpoa(): BelongsTo
    {
        return $this->belongsTo(Gpoa::class);
    }

    public function president(): BelongsTo
    {
        return $this->belongsTo(User::class, 'president_user_id');
    }

    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adviser_user_id');
    }

    public function typeModel(): BelongsTo
    {
        return $this->belongsTo(GpoaActivityType::class,
            'gpoa_activity_type_id');
    }

    public function modeModel(): BelongsTo
    {
        return $this->belongsTo(GpoaActivityMode::class,
            'gpoa_activity_mode_id');
    }

    public function partnershipTypeModel(): BelongsTo
    {
        return $this->belongsTo(GpoaActivityPartnershipType::class,
            'gpoa_activity_partnership_type_id');
    }

    public function fundSourceModel(): BelongsTo
    {
        return $this->belongsTo(GpoaActivityFundSource::class,
            'gpoa_activity_fund_source_id');
    }

    public function eventHeads(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'gpoa_activity_event_heads');
    }

    public function eventHeadsOnly(): BelongsToMany
    {
        return $this->eventHeads()->wherePivot('role', 'event head');
    }

    public function coheads(): BelongsToMany
    {
        return $this->eventHeads()->wherePivot('role', 'co-head');
    }

    public function participantTypes(): BelongsToMany
    {
        return $this->belongsToMany(StudentYear::class,
            'gpoa_activity_participants');
    }

    protected function partnershipType(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->partnershipTypeModel?->name,
            set: function (?string $value) {
                $key = null;
                $partnership = GpoaActivityPartnershipType::findByName($value);
                if (!$partnership && !is_null($value)) {
                    $partnership = new GpoaActivityPartnershipType();
                    $partnership->name = $value;
                    $partnership->save();
                    $key = $partnership->id;
                } elseif ($partnership) {
                    $key = $partnership->id;
                }
                return [
                    'gpoa_activity_partnership_type_id' => $key
                ];
            } 
        );
    }

    protected function mode(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->modeModel?->name,
            set: function (?string $value) {
                $key = null;
                $mode = GpoaActivityMode::findByName($value);
                if (!$mode && !is_null($value)) {
                    $mode = new GpoaActivityMode();
                    $mode->name = $value;
                    $mode->save();
                    $key = $mode->id;
                } elseif ($mode) {
                    $key = $mode->id;
                }
                return [
                    'gpoa_activity_mode_id' => $key
                ];
            } 
        );
    }

    protected function fundSource(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fundSourceModel?->name,
            set: function (?string $value) {
                $key = null;
                $fund = GpoaActivityFundSource::findByName($value);
                if (!$fund && !is_null($value)) {
                    $fund = new GpoaActivityFundSource();
                    $fund->name = $value;
                    $fund->save();
                    $key = $fund->id;
                } elseif ($fund) {
                    $key = $fund->id;
                }
                return [
                    'gpoa_activity_fund_source_id' => $key
                ];
            } 
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->typeModel?->name,
            set: function (?string $value) {
                $key = null;
                $type = GpoaActivityType::findByName($value);
                if (!$type && !is_null($value)) {
                    $type = new GpoaActivityType();
                    $type->name = $value;
                    $type->save();
                    $key = $type->id;
                } elseif ($type) {
                    $key = $type->id;
                }
                return [
                    'gpoa_activity_type_id' => $key
                ];
            } 
        );
    }

    protected function allAreEventHeads(): Attribute
    {
        $all = true;
        foreach (User::has('position')->notOfPosition(['adviser'])
                ->get() as $user) {
            if (!($this->eventHeads()->where('user_id', $user->id)
                    ->wherePivot('role', 'event head')->first())) {
                $all = false;
                break;
            }
        }
        return Attribute::make(
            get: fn () => $all,
        );
    }

    protected function allAreParticipants(): Attribute
    {
        $all = true;
        foreach (StudentYear::all() as $student) {
            if (!$this->participantTypes()->find($student->id)) {
                $all = false;
                break;
            }
        }
        return Attribute::make(
            get: fn () => $all,
        );
    }

    protected function date(): Attribute
    {
        $start = $this->start_date;
        $end = $this->end_date ? $this->end_date : null;

        if (!$end) {
            $date = $start->format('F j, Y');
        } else if ($start->month === $end->month &&
                $start->year === $end->year) {
            $date = $start->format('F j') . '–' . $end->format('j, Y');
        } else if ($start->month !== $end->month &&
                $start->year === $end->year) {
            $date = $start->format('F j') . ' – ' . $end->format('F j, Y');
        } else {
            $date = $start->format('F j, Y') . ' – ' . $end->format('F j, Y');
        }
        return Attribute::make(
            get: fn () => $date
        );
    }

    protected function fullStatus(): Attribute
    {
        $status = $this->status;
        $currentStep = $this->current_step;
        switch ("{$currentStep}_{$status}") {
        case 'president_draft':
        case 'officers_draft':
            $currentStatus = 'Draft';
            break;
        case 'president_pending':
            $currentStatus = 'Pending President Approval';
            break;
        case 'adviser_pending':
            $currentStatus = 'Pending Adviser Approval';
            break;
        case 'president_returned':
            $currentStatus = 'Returned to President';
            break;
        case 'officers_returned':
            $currentStatus = 'Returned to Officers';
            break;
        case 'president_rejected':
            $currentStatus = 'Rejected by President';
            break;
        case 'adviser_rejected':
            $currentStatus = 'Rejected by Adviser';
            break;
        case 'adviser_approved':
            $currentStatus = 'Approved by Adviser';
            break;
        default:
            $currentStatus = 'Unknown';
        }
        return Attribute::make(
            get: fn () => $currentStatus
        );
    }

    protected function commentsPurpose(): Attribute
    {
        $status = $this->status;
        $currentStep = $this->current_step;
        switch ("{$currentStep}_{$status}") {
        case 'president_pending':
        case 'adviser_pending':
            $commentPurpose = 'For Pending Approval';
            break;
        case 'officers_returned':
        case 'president_returned':
            $commentPurpose = 'Return Reason';
            break;
        case 'president_rejected':
        case 'adviser_rejected':
            $commentPurpose = 'Reject Reason';
            break;
        case 'adviser_approved':
            $commentPurpose = 'Approval Note';
            break;
        default:
            $commentPurpose = 'Comments';
        }
        return Attribute::make(
            get: fn () => $commentPurpose
        );
    }

    public function event(): HasOne
    {
        return $this->hasOne(Event::class);
    }

    public function createEvent(): void
    {
        if (Event::whereBelongsTo($this)->first()) {
            return;
        }
        $event = new Event;
        $event->timezone = self::getTimezone();
        $event->venue = $this->venue;
        $event->automatic_attendance = false;
        $event->accept_evaluation = false;
        $event->gpoaActivity()->associate($this);
        $event->save();
        $date = new EventDate;
        $date->date = $this->start_date;
        $date->start_time = '00:00';
        $date->end_time = '23:59';
        $date->event()->associate($this->event);
        $date->save();
	$accomReport = new AccomReport;
        $accomReport->event()->associate($event);
        $accomReport->status = 'draft';
        $accomReport->current_step = 'officers';
        $accomReport->save();
        EventUpdated::dispatch($event);
        EventDatesChanged::dispatch($event);
    }

    private static function getTimezone(): string
    {
        $timezone = config('timezone');
        if (Format::isTimezoneNumeric($timezone)) {
            return Format::getTimezoneRegion($timezone) ?? 'UTC';
        }
        return $timezone;
    }

    #[Scope]
    protected function forAdviser(Builder $query): void
    {
        $query->where(function ($query) {
            $query->where('status', 'pending')
                ->where('current_step', 'adviser');
        })->orWhere('status', 'approved');
    }

    #[Scope]
    protected function forPresident(Builder $query): void
    {
        $user = User::ofPosition(['president'])->first();
        $query->where('status', 'approved')
            ->orWhere(function ($query) {
                $query->where('status', 'pending')
                    ->where('current_step', 'adviser');
            })->orWhere(function ($query) {
                $query->where('status', 'pending')
                    ->where('current_step', 'president');
            })->orWhere(function ($query) {
                $query->where('status', 'returned')
                    ->where('current_step', 'president');
            })->orWhere(function ($query) {
                $query->where('status', 'rejected')
                    ->where('current_step', 'adviser');
            })->orWhereAttachedTo($user, 'eventHeads');
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereHas('gpoa', function ($query) {
            $query->where('active', 1);
        });
    }
}
