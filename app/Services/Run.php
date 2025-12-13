<?php

namespace App\Services;

use App\Models\Gpoa;

class Run
{
    public function __construct()
    {
        //
    }

    public static function run()
    {
        $activities = Gpoa::find(4)->activities;
        foreach ($activities as $activity) {
           $activity->event?->accomReport()?->delete();
           $activity->event?->dates()?->delete();
           $activity->event?->delete();
           $activity->eventHeads()?->detach();
        }
        Gpoa::find(4)->activities()?->delete();
    }
}
