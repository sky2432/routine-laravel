<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public static function deleteImage($image_url)
    {
        $file_name = basename($image_url);
        if (Storage::disk('s3')->exists($file_name) && $image_url !== config('const.DEFAULT_IMAGE_URL')) {
            Storage::disk('s3')->delete($file_name);
        } else {
            return;
        }
    }

    public static function uploadImage($request)
    {
        $path = Storage::disk('s3')->putFile('/', $request->file('image'), 'public');
        $url = Storage::disk('s3')->url($path);
        return $url;
    }
}
