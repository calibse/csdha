<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image as IImage;

class Image
{
    private $image;

    public function __construct($image)
    {
        $this->image = $image;
    }

    public function scaleDown($imageSize)
    {
        $image = IImage::read($this->image);
        if ($image->width() !== $imageSize && $image->width() <= $image->height()) {
            $image->scaleDown(width: $imageSize);
        }
        elseif ($image->height() !== $imageSize && $image->height() <= $image->width()) {
            $image->scaleDown(height: $imageSize);
        }

        return $image->toJpeg();
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
