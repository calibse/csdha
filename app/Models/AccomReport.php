<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AccomReport extends Model
{
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'returned_at' => 'datetime',
            'approved_at' => 'datetime',
            'file_updated_at' => 'datetime',
        ];
    }

    public function president(): BelongsTo
    {
        return $this->belongsTo(User::class, 'president_user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    protected function statusColor(): Attribute
    {
        $status = $this->status;
        $step = $this->current_step;
        $position = auth()->user()->position_name;
        if (!in_array($position, ['president', 'adviser', null])) {
            $position = 'officers';
        }
        switch ("{$position}_{$step}_{$status}") {
        case 'officers_officers_returned':
        case 'president_president_pending':
            $color = 'red';
            break;
        case 'officers_president_pending':
        case 'officers_officers_draft':
            $color = 'yellow';
            break;
        case 'officers_adviser_approved':
        case 'president_adviser_approved':
        case 'adviser_adviser_approved':
            $color = 'green';
            break;
        default:
            $color = 'black';
        }
        return Attribute::make(
            get: fn () => $color,
        );
    }

    public function fullStatus(): Attribute
    {
        $status = $this->status;
        $step = $this->current_step;
        $fullStatus = '';
        switch ("{$status}_{$step}") {
        case 'approved_adviser':
            $fullStatus = 'Approved by President';
            break;
        case 'returned_officers':
            $fullStatus = 'Returned to Officers';
            break;
        case 'pending_president':
            $fullStatus = 'Pending President Approval';
            break;
        case 'draft_officers':
            $fullStatus = 'Draft';
            break;
        default:
            $fullStatus = 'Unknown';
        }
        return Attribute::make(
            get: fn () => $fullStatus,
        );
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->whereHas('event.gpoaActivity.gpoa', function ($query) {
            $query->where('active', 1);
        });
    }

    #[Scope]
    protected function forAdviser(Builder $query): void
    {
        $query->where('status', 'approved');
    }

    #[Scope]
    protected function forPresident(Builder $query): void
    {
        $query->where('status', 'approved')->orWhere(function ($query) {
            $query->where('status', 'pending')
                ->where('current_step', 'president');
        });
    }
}
