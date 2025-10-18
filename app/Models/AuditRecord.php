<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditRecord extends Model
{
    protected $table = 'audit_trail';

    protected function casts(): array
    {
        return [
            'request_time' => 'datetime'
        ];
    }
}
