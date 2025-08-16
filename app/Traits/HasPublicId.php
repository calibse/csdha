<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait HasPublicId
{
    public static function bootHasPublicId()
    {
        static::created(function ($model) {
            $model->public_id = random_int(100000, 999999) . $model->id;
            $model->save();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public static function findByPublic($id)
    {
        return static::firstWhere('public_id', $id);
    }

    public static function genPublicId()
    {
        $table = (new static)->getTable();
        if (!Schema::hasColumn($table, 'public_id')) return;
        $rows = static::all();
        foreach ($rows as $row) {
            if ($row->public_id !== null) continue;
            $row->public_id = random_int(100000, 999999) . $row->id;
            $row->save();
        }
    }
}
