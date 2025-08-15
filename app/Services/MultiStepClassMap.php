<?php

namespace App\Services;

use App\Services\EvalFormStep;
use App\Services\EventRegisStep;

class MultiStepClassMap
{
    private static array $map;

    private static function boot(): void
    {
        self::$map = [
            'events.eval-form' => EvalFormStep::class,
            'events.registrations' => EventRegisStep::class
        ];
    }

    public static function getClass($route)
    {
        self::boot();
        foreach (array_keys(self::$map) as $key) {
            if (str_starts_with($route, $key)) {
                return self::$map[$key];
            }
        }
        throw new InvalidArgumentException('Invalid route name.');
    }
}
