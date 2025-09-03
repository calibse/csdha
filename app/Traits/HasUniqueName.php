<?php

namespace App\Traits;

trait HasUniqueName
{
    public static function findByName($name)
    {
        $name = strtolower($name);
        return static::whereRaw('lower(name) = ?', [$name])->first();
    } 
}
