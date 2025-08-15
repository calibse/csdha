<?php

namespace App\Services;

use Illuminate\Process\Pipe;
use Illuminate\Support\Facades\Process;

class Stream
{
    public static function process(array $commands): void
    {
        Process::pipe(function (Pipe $pipe) use ($commands) {
            $last = array_key_last($commands);
            foreach ($commands as $i => $command) {
                if ($i === $last) {
                    $pipe->as('final')->command($command);
                } else {
                    $pipe->command($command);
                }
            }
        }, function (string $type, string $output, string $key) {
            if ($key === 'final') {
                echo $output;
                ob_flush();
                flush();
            }
        });
    }
}
