<?php

namespace App\Listeners;

use App\Events\GpoaStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use App\Models\Gpoa;
use App\Services\Stream;

class EmitGpoaStatusStream
{

    public function __construct()
    {
    }

    public function handle(GpoaStatusChanged $event): void
    {
        $cache = 'home_stream';
        $event = 'gpoaStatusChanged';
        $gpoaActive = Gpoa::active()->exists();
        $data = [
            'active' => $gpoaActive
        ];
        Stream::store($cache, $event, $data);
    }
}
