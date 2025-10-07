<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUniqueName;

class GpoaActivityFundSource extends Model
{
    use HasUniqueName, SoftDeletes;
}
