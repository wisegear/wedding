<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryUpload;
use App\Models\Guest;
use App\Models\GuestGroup;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $weddingDate = CarbonImmutable::parse(
            config('wedding.date'),
            config('wedding.timezone', config('app.timezone')),
        );

        return view('admin.dashboard', [
            'weddingDate' => $weddingDate,
            'venue' => config('wedding.venue'),
            'guestGroupCount' => GuestGroup::query()->count(),
            'guestCount' => Guest::query()->count(),
            'pendingGalleryCount' => GalleryUpload::query()->where('approved', false)->count(),
        ]);
    }
}
