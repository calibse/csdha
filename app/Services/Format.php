<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use App\Traits\HasPublicId;

class Format
{
    public static function ordinal($number) 
    {
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
            case 1:  return $number . 'st';
            case 2:  return $number . 'nd';
            case 3:  return $number . 'rd';
            }
        }
        return $number . 'th';
    }

    public static function toPh($dateTime)
    { 
        return $dateTime 
            ? Carbon::parse($dateTime, 'UTC')->setTimezone('Asia/Manila')
            : null;
    }

    public static function toLocal($dateTime)
    { 
        return $dateTime 
            ? Carbon::parse($dateTime, 'UTC')->setTimezone(config('timezone'))
            : null;
    }

    public static function toUtc($dateTime)
    { 
        return $dateTime 
            ? Carbon::parse($dateTime, config('timezone'))->utc()
            : null;
    }

    public static function genAllPublicIds()
    {
        $models = collect(File::allFiles(app_path('Models')))
            ->map(fn ($file) => 'App\\Models\\' . $file->getFilenameWithoutExtension())
            ->filter(fn ($class) => in_array(HasPublicId::class, class_uses($class)));
        foreach ($models as $model) {
            $model::genPublicId();
        }
    }

    public static function getOpt($selected, $allModels): array
    {
        $options = [];
        $options['selected'] = [];
        $options['unselected'] = [];
        if (is_array($selected)) {
            foreach ($allModels as $model) {
                if (in_array($model->id, $selected)
                    || in_array($model->public_id, $selected)) {
                    $options['selected'][] = $model;
                } else {
                    $options['unselected'][] = $model;
                }
            }
        } elseif ($selected instanceof \Illuminate\Support\Collection) {
            foreach ($allModels as $model) {
                if ($selected->contains($model)) {
                    $options['selected'][] = $model;
                } else {
                    $options['unselected'][] = $model;
                }
            }
        }
        return $options;
    }


}
