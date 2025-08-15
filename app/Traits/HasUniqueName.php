<?php

namespace App\Traits;

trait HasUniqueName
{
    public static function findByName($name)
    {
        $name = strtolower($name);
        static::whereRaw('lower(name) = ?', [$name])->first();
    } 
}
