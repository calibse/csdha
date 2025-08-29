<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Process\Pipe;
use App\Services\Stream;

class QrCode
{
    private string $data;
    private ?string $caption;
    private ?string $topCaption;
    private ?string $bottomCaption;
    private string $imageType;
    private string $contentType;

    public function __construct($data, $topCaption = null, 
            $bottomCaption = null)
    {
        $this->data = $data;
        $this->topCaption = $topCaption;
        $this->bottomCaption = $bottomCaption;
        $this->imageType = 'png';
        $this->contentType = 'image/png';
    }

    public function stream()
    {
        $formatUp = strtoupper($this->imageType);
        $formatLo = strtolower($this->imageType);
        $topCaption = escapeshellarg($this->topCaption);
        $bottomCaption = escapeshellarg($this->bottomCaption);
        $magick = "magick {$formatLo}:- -gravity south -splice 0x128 " .
            "-background white -fill black -font Cantarell -pointsize 48 " .
            "-gravity south -annotate +0+80 {$bottomCaption} " .
            "-gravity north -splice 0x128 -gravity north -annotate +0+80 " .
            "{$topCaption} {$formatLo}:-";
        $qrencode = "qrencode -l M -s 20 -t {$formatUp} -o - {$this->data}";
        if ($this->topCaption && $this->bottomCaption) {
            $stream = fn () => Stream::process([$qrencode, $magick]);
        } 
        else {
            $stream = fn () => Stream::process([$qrencode]);
        }

        return response()->stream($stream, 200, [
            'Content-Type' => $this->contentType,
            'X-Accel-Buffering' => 'no'
        ]);
    }
}
