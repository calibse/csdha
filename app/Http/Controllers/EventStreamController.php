<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\StreamedEvent;
use Illuminate\Support\Facades\Cache;

class EventStreamController extends Controller
{
    public function home()
    {
        return response()->eventStream(function () {
            while (true) {
                $cache = 'home_stream';
                Cache::lock($cache . '_lock', 2)->block(1, function () {
                    $stream = Cache::get($cache, []);
                    Cache::forget($cache);
                });
                foreach ($stream as $response) {
                    yield new StreamedEvent(
                        event: $response['event'],
                        data: $response['data']
                    );
                }
                if (connection_aborted()) break;
                sleep(1);
            }
        });
    }
}
