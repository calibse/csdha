<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateLogoRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Imagick;

class AssetController extends Controller
{
    public function editLogo()
    {
        return view('settings.edit-logo', [
            'backRoute' => route('settings.index'),
            'formAction' => route('settings.logos.update')
        ]);
    }

    public function updateLogo(UpdateLogoRequest $request)
    {
        if ($request->has('website')) {
            $image = new Image($request->file('website'));
            $filepath = 'website-logo.png';
            Storage::disk('public')->put($filepath, $image->toPng());
            Cache::put('website_logo_id', Str::random(8));
            if (app()->environment('production')) {
                self::updateFavicon(public_path('storage/' . $filepath));
            }
        }
        if ($request->has('organization')) {
            $image = new Image($request->file('organization'));
            $filepath = 'organization-logo.png';
            Storage::disk('public')->put($filepath, $image->toPng());
            Cache::put('organization_logo_id', Str::random(8));
        }
        if ($request->has('university')) {
            $image = new Image($request->file('university'));
            $filepath = 'university-logo.png';
            Storage::disk('public')->put($filepath, $image->toPng());
            Cache::put('university_logo_id', Str::random(8));
        }
        return redirect()->route('settings.logos.edit')
            ->with('status', 'Logos updated.');
    }

    private static function updateFavicon($source)
    {
        $iconPath = public_path('favicon.ico');
        $ico = new Imagick();
        $sizes = [16, 32, 48, 64, 128, 256];
        foreach ($sizes as $size) {
            $img = new Imagick($source);
            $img->resizeImage($size, $size, Imagick::FILTER_LANCZOS, 1);
            $ico->addImage($img);
        }
        $ico->setFormat('ico');
        $ico->writeImage($iconPath);
    }
}
