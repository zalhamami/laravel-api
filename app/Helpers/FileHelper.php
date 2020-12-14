<?php

namespace App\Helpers;

class FileHelper
{
    public static function getFileName($file, $customName = NULL)
    {
        $fileName = time();
        if ($customName) {
            $fileName .= '-' . $customName . '.' . $file->getClientOriginalExtension();
        } else {
            $fileName .= '.' . $file->getClientOriginalExtension();
        }
        return $fileName;
    }
}
