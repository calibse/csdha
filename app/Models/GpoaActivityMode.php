<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUniqueName;

class GpoaActivityMode extends Model
{
    use HasUniqueName, SoftDeletes;
}
