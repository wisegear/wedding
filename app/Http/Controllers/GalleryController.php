<?php

namespace App\Http\Controllers;

use App\Models\GalleryUpload;
use App\Services\GalleryImageProcessor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GalleryController extends Controller
{
    public function __construct(
        private readonly GalleryImageProcessor $galleryImageProcessor,
    ) {}

    public function __invoke(): View
    {
        return view('pages.gallery', [
            'uploads' => GalleryUpload::query()
                ->where('approved', true)
                ->latest()
                ->paginate(10),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,heic,heif', 'max:20480'],
        ]);

        $uploadedCount = 0;
        $failedFiles = [];

        foreach ($request->file('images', []) as $image) {
            try {
                $storedPaths = $this->galleryImageProcessor->store($image);

                GalleryUpload::query()->create([
                    'uploaded_by' => $request->user()->id,
                    'original_filename' => $storedPaths['original_filename'],
                    'original_path' => $storedPaths['original_path'],
                    'display_path' => $storedPaths['display_path'],
                    'approved' => false,
                ]);

                $uploadedCount++;
            } catch (\Throwable) {
                $failedFiles[] = $image->getClientOriginalName();
            }
        }

        if ($uploadedCount === 0) {
            throw ValidationException::withMessages([
                'images' => 'We could not process your photos. Please try again with different image files.',
            ]);
        }

        $message = $uploadedCount.' '.Str::plural('photo', $uploadedCount).' uploaded successfully. They will appear once approved.';

        if ($failedFiles !== []) {
            $message .= ' '.count($failedFiles).' file'.(count($failedFiles) === 1 ? ' was' : 's were').' skipped because they could not be processed.';
        }

        return redirect()
            ->route('gallery')
            ->with('status', $message);
    }
}
