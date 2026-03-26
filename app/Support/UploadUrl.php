<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class UploadUrl
{
    public static function url(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $disk = config('filesystems.upload_disk', 'public');

        return Storage::disk($disk)->url($path);
    }
}
