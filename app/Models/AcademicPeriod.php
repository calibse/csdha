<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class AcademicPeriod extends Model
{
    protected static function booted(): void
    {
        static::saving(function (AcademicPeriod $period) {
            if ($period->term->term_number === 1) {
                $period->year_label = Carbon::parse($period->start_date)->year 
                    . ' - ' . Carbon::parse($period->start_date)->addYear()
                    ->year;
            } else {
                $period->year_label = Carbon::parse($period->end_date)
                    ->subYear()->year . ' - ' 
                    . Carbon::parse($period->end_date)->year;
            }
        });
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class, 'academic_term_id');
    }
}
