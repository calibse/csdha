<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class AccomReport extends Model
{
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'returned_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
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
