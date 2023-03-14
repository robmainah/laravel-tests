<?php

namespace App\Services;

use ZipArchive;

class ZipperService
{
    public static function createZipFile($fileName) 
    {
        $zip = new ZipArchive();
        $zipFileName = storage_path('app/public/temp/' . now()->timestamp . '-task.zip');

        if ($zip->open($zipFileName, ZipArchive::CREATE) == true) {
            $zip->addFile(storage_path('app/public/temp/' . $fileName), $fileName);
            $zip->close();
        }

        return $zipFileName;
    }
}
