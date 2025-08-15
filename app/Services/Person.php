<?php

namespace App\Services;

use App\Models\User;

class Person
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    
    public static function greet() 
    {
		$user = User::find(1);
		return "hello";
	}
}
