<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;
use ImagickPixel;

class GalleryImageProcessor
{
    /**
     * @return array{original_path: string, display_path: string, original_filename: string}
     */
    public function store(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $baseName = (string) Str::uuid();
        $originalPath = 'gallery/originals/'.$baseName.'.'.$extension;
        $displayPath = 'gallery/display/'.$baseName.'.jpg';

        $disk = Storage::disk('public');

        try {
            $disk->putFileAs('gallery/originals', $file, $baseName.'.'.$extension);
            $disk->put($displayPath, $this->makeDisplayImage($file));

            return [
                'original_path' => $originalPath,
                'display_path' => $displayPath,
                'original_filename' => $file->getClientOriginalName(),
            ];
        } catch (\Throwable $exception) {
            $disk->delete([$originalPath, $displayPath]);

            throw $exception;
        }
    }

    private function makeDisplayImage(UploadedFile $file): string
    {
        $image = new Imagick();
        $image->readImage($file->getRealPath().'[0]');

        if (method_exists($image, 'autoOrient')) {
            $image->autoOrient();
        }

        $width = $image->getImageWidth();
        $height = $image->getImageHeight();
        $longestSide = max($width, $height);

        if ($longestSide > 1600) {
            $scale = 1600 / $longestSide;

            $image->resizeImage(
                (int) round($width * $scale),
                (int) round($height * $scale),
                Imagick::FILTER_LANCZOS,
                1,
                true,
            );
        }

        $image->stripImage();
        $image->setImageBackgroundColor(new ImagickPixel('white'));
        $image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
        $image->setImageFormat('jpeg');
        $image->setImageCompressionQuality(80);

        return $image->getImageBlob();
    }
}
