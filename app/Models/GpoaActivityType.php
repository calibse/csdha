<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUniqueName;

class GpoaActivityType extends Model
{
    use HasUniqueName, SoftDeletes;
}
