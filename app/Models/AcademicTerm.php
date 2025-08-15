<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Format;

class AcademicTerm extends Model
{
    protected static function booted(): void
    {
        static::saving(function (AcademicTerm $term) {
            $term->label = sprintf('%s %s', Format::ordinal($term->term_number), 
                ucwords($term->system));
        });
    }
}
