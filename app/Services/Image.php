<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image as IImage;
use Imagick;
use ImagickPixel;

class Image
{
    public function __construct(private $image)
    {

    }

    public function scaleDown($imageSize)
    {
        $image = IImage::read($this->image);
        $image->orient();
        if ($image->width() !== $imageSize && $image->width() <=
                $image->height()) {
            $image->scaleDown(width: $imageSize);
        }
        elseif ($image->height() !== $imageSize && $image->height()
                <= $image->width()) {
            $image->scaleDown(height: $imageSize);
        }
        return $image->toJpeg();
    }

    public function get()
    {
        $image = IImage::read($this->image);
        $image->orient();
        return $image->toJpeg();
    }

    public function toPng()
    {
	config(['image.driver' => \Intervention\Image\Drivers\Imagick\Driver::class]);
        $mime = $this->image->getMimeType();
	if ($mime !== 'image/svg+xml') {
            $image = IImage::read($this->image);
            return $image->toPng();
        }
/*
        $file = file_get_contents($this->image->getRealPath());
        $imagick = new Imagick();
        $imagick->setBackgroundColor(new ImagickPixel('transparent'));
        $imagick->readImageBlob($file);
        $imagick->setImageFormat('png32');
*/
        $svgPath = $this->image->getRealPath();
        $pngData = shell_exec("rsvg-convert -f png " . escapeshellarg($svgPath));
        $imagick = new Imagick();
        $imagick->readImageBlob($pngData);
        $imagick->setImageFormat('png32'); 
        $image = IImage::read($imagick);
        $image->orient();
        return $image->toPng();
    }

    public function orientation()
    {
        $image = IImage::read($this->image);
        $width = $image->width();
        $height = $image->height();
        $portraitThreshold = 1.25 * $height;
        if ($width > $height && $width > $portraitThreshold) {
            $orientation = 'landscape';
        } elseif ($width < $height || $width <= $portraitThreshold) {
            $orientation = 'portrait';
        } else {
            $orientation = 'square';
        }
        return $orientation;
    }
}
