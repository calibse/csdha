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

class GpoaActivity extends Model
{
    use HasPublicId;

    protected function casts(): array
    {
        return [
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

    public function type(): BelongsTo
    {
        return $this->belongsTo(GpoaActivityType::class, 
            'gpoa_activity_type_id');
    }

    public function mode(): BelongsTo
    {
        return $this->belongsTo(GpoaActivityMode::class, 
            'gpoa_activity_mode_id');
    }

    public function partnershipType(): BelongsTo
    {
        return $this->belongsTo(GpoaActivityPartnershipType::class, 
            'gpoa_activity_partnership_type_id');
    }

    public function fundSource(): BelongsTo
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

    public function allAreEventHeads(): Attribute 
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

    public function allAreParticipants(): Attribute 
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

    public function date(): Attribute 
    {
        $start = Carbon::parse($this->start_date);
        $end = $this->end_date ? Carbon::parse($this->end_date) : null;

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

    public function currentStatus(): Attribute 
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
            $currentStatus = 'Returned to President for Update';
            break;
        case 'officers_returned':
            $currentStatus = 'Returned to Officers for Update';
            break;
        case 'president_rejected':
            $currentStatus = 'Rejected by President';
            break;
        case 'adviser_rejected':
            $currentStatus = 'Rejected by Adviser';
            break;
        case 'adviser_approved':
            $currentStatus = 'Approved';
            break;
        default:
            $currentStatus = 'Unknown';
        }
        return Attribute::make(
            get: fn () => $currentStatus
        );
    }

    public function commentsPurpose(): Attribute 
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
        $event = new Event();
        $event->venue = $this->venue;
        $event->gpoaActivity()->associate($this);
        $event->save();
        $date = new EventDate();
        $date->date = $this->start_date;
        $date->event()->associate($this->event);
        $date->save();
    }

    
    #[Scope]
    protected function forAdviser(Builder $query): void
    {
        $query->where(function ($query) {
                $query->where('status', 'pending')
                    ->where('current_step', 'adviser');
            }) ->orWhere('status', 'approved');
    }

    #[Scope]
    protected function forPresident(Builder $query): void
    {
        $user = User::ofPosition(['president'])->first();
        $query->where('status', 'approved')
            ->orWhere(function ($query2) {
                $query2->where('status', 'pending')
                    ->where('current_step', 'adviser');
            })->orWhere(function ($query2) {
                $query2->where('status', 'pending')
                    ->where('current_step', 'president');
            })->orWhere(function ($query2) {
                $query2->where('status', 'returned')
                    ->where('current_step', 'president');
            })->orWhere(function ($query2) {
                $query2->where('status', 'rejected')
                    ->where('current_step', 'adviser');
            })->orWhereAttachedTo($user, 'eventHeads');
    }

}
