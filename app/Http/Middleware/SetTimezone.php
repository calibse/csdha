<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Format;

class SetTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        $timezone = $request->cookie('timezone');
        if (is_null($timezone)) {
            config(['timezone' => 'UTC']);
        } elseif (Format::isTimezoneRaw($timezone)) {
            $timezone = Format::getNumericTimezone($timezone);
            config(['timezone' => $timezone]);
        } elseif (Format::isTimezoneValid($timezone)) {
            config(['timezone' => $timezone]);
        } else {
            config(['timezone' => 'UTC']);
        }
        return $next($request);
    }
}
