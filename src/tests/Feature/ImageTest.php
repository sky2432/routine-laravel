<?php

namespace Tests\Feature;

use App\Services\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function test_upload_image()
    {
        $uploadedFile = UploadedFile::fake()->image('design.png');

        $url = ImageService::uploadImage($uploadedFile);
        $file_name = basename($url);

        Storage::disk('s3')->assertExists($file_name);

        ImageService::deleteImage($url);

        Storage::disk('s3')->assertMissing($file_name);
    }
}
