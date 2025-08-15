<?php

namespace App\Traits;

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
}
