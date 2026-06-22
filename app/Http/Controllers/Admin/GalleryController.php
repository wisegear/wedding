<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', [
            'pendingUploads' => GalleryUpload::query()
                ->with('user')
                ->where('approved', false)
                ->latest()
                ->get(),
            'approvedUploads' => GalleryUpload::query()
                ->with('user')
                ->where('approved', true)
                ->latest()
                ->get(),
        ]);
    }

    public function approve(GalleryUpload $galleryUpload): RedirectResponse
    {
        $galleryUpload->update([
            'approved' => true,
        ]);

        return redirect()
            ->route('admin.gallery.index')
            ->with('status', 'Photo approved and added to the public gallery.');
    }

    public function unapprove(GalleryUpload $galleryUpload): RedirectResponse
    {
        $galleryUpload->update([
            'approved' => false,
        ]);

        return redirect()
            ->route('admin.gallery.index')
            ->with('status', 'Photo removed from the public gallery.');
    }
}
