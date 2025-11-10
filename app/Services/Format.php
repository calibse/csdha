<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use App\Traits\HasPublicId;
use DateTimeZone;

class Format
{
    public static function ordinal($number)
    {
        if (!in_array(($number % 100), [11, 12, 13])) {
            switch ($number % 10) {
            case 1:  return $number . 'st';
            case 2:  return $number . 'nd';
            case 3:  return $number . 'rd';
            }
        }
        return $number . 'th';
    }

    public static function toPh($dateTime)
    {
        return $dateTime
            ? Carbon::parse($dateTime, 'UTC')->setTimezone('Asia/Manila')
            : null;
    }

    public static function toLocal($dateTime)
    {
        return $dateTime
            ? Carbon::parse($dateTime, 'UTC')->setTimezone(config('timezone'))
            : null;
    }

    public static function toUtc($dateTime)
    {
        return $dateTime
            ? Carbon::parse($dateTime, config('timezone'))->utc()
            : null;
    }

    public static function genAllPublicIds()
    {
        $models = collect(File::allFiles(app_path('Models')))
            ->map(fn ($file) => 'App\\Models\\' . $file->getFilenameWithoutExtension())
            ->filter(fn ($class) => in_array(HasPublicId::class, class_uses($class)));
        foreach ($models as $model) {
            $model::genPublicId();
        }
    }

    public static function getOpt($selected, $allModels): array
    {
        $options = [];
        $options['selected'] = [];
        $options['unselected'] = [];
        if (is_array($selected)) {
            foreach ($allModels as $model) {
                if (in_array($model->id, $selected)
                    || in_array($model->public_id, $selected)) {
                    $options['selected'][] = $model;
                } else {
                    $options['unselected'][] = $model;
                }
            }
        } elseif ($selected instanceof \Illuminate\Support\Collection) {
            foreach ($allModels as $model) {
                if ($selected->contains($model)) {
                    $options['selected'][] = $model;
                } else {
                    $options['unselected'][] = $model;
                }
            }
        }
        return $options;
    }

    public static function getResourceRoute($request): string
    {
        $str = $request->route()->getName();
        $routeName = substr($str, 0, strrpos($str, '.'));
        return $routeName;
    }

    public static function roman($num)
    {
        $map = [
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1
        ];
        $result = '';
        foreach ($map as $roman => $value) {
            while ($num >= $value) {
                $result .= $roman;
                $num -= $value;
            }
        }
        return $result;
    }

    public static function localSeconds(int $seconds, string $timezone): int
    {
        $nowUtc = Carbon::now('UTC');
        $nowTz = $nowUtc->copy()->setTimezone($timezone);
        $offsetSeconds = $nowTz->getOffset();
        $normalizedSeconds = $seconds - $offsetSeconds;
        return $normalizedSeconds;
    }

    public static function getNumericTimezone($jsOffsetMinutes)
    {
        $offsetMinutes = -$jsOffsetMinutes;
        $sign = ($offsetMinutes >= 0) ? '+' : '-';
        $hours = intdiv(abs($offsetMinutes), 60);
        $minutes = abs($offsetMinutes) % 60;
        $tzString = sprintf('%s%02d:%02d', $sign, $hours, $minutes);
        return $tzString;
    }

    public static function isTimezoneRaw($value)
    {
        return (is_int($value) || (is_numeric($value) && 
            (int)$value == $value));
    }

    public static function isTimezoneNumeric($timezone)
    { 
        return (isset($timezone[0]) && ($timezone[0] === '+' || 
            $timezone[0] === '-'));
    }

    public static function isTimezoneValid($value)
    { 
        return (is_string($value) && in_array($value, 
            DateTimeZone::listIdentifiers()));
    }

    public static function getTimezoneRegion($offset) {
        $sign = $offset[0];
        list($hours, $minutes) = explode(':', substr($offset, 1));
        $seconds = ($hours * 3600 + $minutes * 60) * ($sign === '+' ? 1 : -1);
        foreach (timezone_identifiers_list() as $zone) {
            $tz = new DateTimeZone($zone);
            $transitions = $tz->getTransitions(time(), time());
            if (isset($transitions[0]['offset']) && 
                $transitions[0]['offset'] === $seconds) {
                return $zone; // return the first matching region
            }
        }
        return null;
    }

    public static function date(Carbon $date)
    {
        if (config('timezone') === 'UTC') {
            return $date->timezone(config('timezone'))
                ->format(config('app.date_format')) . ' UTC';
        }
        return $date->timezone(config('timezone'))
            ->format(config('app.date_format'));
    }

/*
    public static function legacyScriptTag($entry)
    {
        $manifestPath = public_path('build/manifest.json');
        $filename = '';
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $entry = trim($entry, "'\"");
            if (isset($manifest[$entry])) {
                $filename = $manifest[$entry]['file'];
            }
        }
        if (true || $filename) {
            return '<script nomodule defer src="' . asset('build/' . $filename)                 . '"></script>';
        }
        return '';
    }
*/

    public static function legacyScriptTag($entry)
    {
        $manifestPath = public_path('build-legacy/manifest.json');
        if (!file_exists($manifestPath)) {
            return '';
        }
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $entry = trim($entry, "'\"");
        $tags = [];
        if (isset($manifest[$entry])) {
            $filename = $manifest[$entry]['file'];
            $tags[] = '<script defer src="' . asset('build-legacy/' . 
                $filename) . '"></script>';
        }
        return implode("\n", $tags);
    }

    public static function documentPrepareMessage(): string
    {
        $messages = [
            'Document generation in progress...',
            'Working on your document...',
            'Setting up document data...',
            'Processing request for document...',
            'Preparing file contents...',
            'Compiling information for document...'
        ];
        return $messages[array_rand($messages)] . ' Page will auto-refresh.';
    }

    public static function documentUpdateMessage(): string
    {
        $messages = [
            'Document is being updated...',
            'Processing document update...',
            'Updating document content...',
            'Compiling updated document data...',
            'Preparing the updated version of the document...',
            'Generating latest document updates...'
        ];
        return $messages[array_rand($messages)] . ' Page will auto-refresh.';
    }

    public static function currentRoute(string $route): bool
    {
        $urlMatched = str_starts_with(url()->full(), $route);
        $hasQuery = true;
        parse_str(parse_url($route)['query'] ?? '', $query);
        if (!$query && request()->query()) $hasQuery = false;
        foreach ($query as $name => $value) {
            if (request()->query($name) !== $value) {
                $hasQuery = false;
                break;
            }
        }
        return ($urlMatched && $hasQuery) ? true : false;
    }
}
