<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ServerStatisticController extends Controller
{
    public function show()
    {
        $sizes = [];
        $sizes['disk_total'] = disk_total_space(base_path());
        $sizes['disk_free'] = disk_free_space(base_path());
        $sizes['disk_used'] = $sizes['disk_total'] - $sizes['disk_free'];
        if (config('database.default') === 'sqlite') {
            $sizes['db_usage'] = DB::scalar('select page_count * page_size 
                as db_usage from pragma_page_count, pragma_page_size');
        } else {
            $sizes['db_usage'] = DB::table('information_schema.tables')
                ->selectRaw('sum("data_length" + "index_length") as "db_usage"')
                ->where('table_schema', 
                    config('database.connections.mariadb.database'))
                ->groupBy('table_schema')->value('db_usage');
        }
        $sizes['file_storage_usage'] = self::getDirectorySize(
            storage_path('app'));
        $memoryLimit = ini_get('memory_limit');
        $uploadSizeLimit = ini_get('upload_max_filesize');
		foreach ($sizes as $sizeName => $sizeValue) {
			$sizes[$sizeName] = self::formatBytes($sizeValue);
		}
        return view('home.admin', [
            'diskTotal' => $sizes['disk_total'],
            'diskFree' => $sizes['disk_free'],
            'diskUsed' => $sizes['disk_used'],
            'dbUsage' => $sizes['db_usage'],
            'fileStorageUsage' => $sizes['file_storage_usage'],
            'memoryLimit' => $memoryLimit,
            'uploadSizeLimit' => $uploadSizeLimit,
        ]);
    }

    private static function formatBytes($bytes)
    {
        $units = ['B', 'K', 'M', 'G', 'T'];
        $precision = 2;

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . $units[$pow];
    }

    private static function getDirectorySize($path)
    {
        $size = 0;
        foreach (File::allFiles($path) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }
}
