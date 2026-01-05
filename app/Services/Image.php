<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image as IImage;
use Imagick;
use ImagickPixel;

class Image
{
    private $image;

    public function __construct(private $file)
    {
        $this->image = IImage::read($this->file);
    }

    public function scaleDown($imageSize)
    {
        $image = $this->image;
        $image->scaleDownImage($imageSize);
        return $image->toJpeg();
    }

    public function scaleDownImage($imageSize)
    {
        $image = $this->image;
        if ($image->width() !== $imageSize && $image->width() <=
                $image->height()) {
            $image->scaleDown(width: $imageSize);
        }
        elseif ($image->height() !== $imageSize && $image->height()
                <= $image->width()) {
            $image->scaleDown(height: $imageSize);
        }
    }

    public function get()
    {
        $image = $this->image;
        return $image->toJpeg();
    }

    public function toFavicon()
    {
        $image = $this->image;
        $image->scaleDownImage(16);
        return $image->toPng();
    }

    public function toLogo()
    {
        $image = $this->image;
        $image->scaleDownImage(128);
        return $image->toPng();
    }

    public function toPng()
    {
        $image = $this->image;
        return $image->toPng();
	// config(['image.driver' => \Intervention\Image\Drivers\Imagick\Driver::class]);
        // $mime = $this->image->getMimeType();
	if ($mime !== 'image/svg+xml') {
        }
        $svgPath = $this->image->getRealPath();
        $pngData = shell_exec("rsvg-convert -f png " . escapeshellarg($svgPath));
        $imagick = new Imagick();
        $imagick->readImageBlob($pngData);
        $imagick->setImageFormat('png32'); 
        $image = IImage::read($imagick);
        return $image->toPng();
    }

    public function orientation()
    {
        $image = $this->image;
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
