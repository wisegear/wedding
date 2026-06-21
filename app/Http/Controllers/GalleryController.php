<?php

namespace App\Http\Controllers;

use App\Models\GalleryUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.gallery', [
            'uploads' => GalleryUpload::query()
                ->where('approved', true)
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:10240'],
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('image')->store('wedding-gallery', 'public');

        GalleryUpload::query()->create([
            'user_id' => $request->user()->id,
            'original_filename' => $request->file('image')->getClientOriginalName(),
            'path' => $path,
            'caption' => $validated['caption'] ?? null,
            'approved' => false,
        ]);

        return redirect()
            ->route('gallery')
            ->with('status', 'Photo uploaded successfully. It will appear once approved.');
    }
}
