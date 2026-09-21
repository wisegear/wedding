<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use RuntimeException;

class WeddingPartyPhotoProcessor
{
    public function store(UploadedFile $photo): string
    {
        $image = ImageManager::imagick()->read($photo)->cover(200, 200);
        $path = 'wedding-party/'.Str::uuid().'.jpg';

        if (! Storage::disk('public')->put($path, (string) $image->toJpeg(quality: 85, strip: true))) {
            throw new RuntimeException('The wedding party photo could not be saved.');
        }

        return $path;
    }
}
