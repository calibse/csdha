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
        $file = file_get_contents($this->image->getRealPath());
        $imagick = new Imagick();
        $imagick->setBackgroundColor(new ImagickPixel('transparent'));
        $imagick->readImageBlob($file);
        $imagick->setImageFormat('png32');
        $image = IImage::read($imagick);
        return $image->toPng();
    }

    public function orientation()
    {
        $image = IImage::read($this->image);
        $width = $image->width();
        $height = $image->height();
        if ($width > $height) {
            $orientation = 'landscape';
        } elseif ($width < $height) {
            $orientation = 'portrait';
        } else {
            $orientation = 'square';
        }
        return $orientation;
    }
}
