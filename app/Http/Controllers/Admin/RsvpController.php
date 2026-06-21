<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeddingGuest;
use Illuminate\Contracts\View\View;

class RsvpController extends Controller
{
    public function index(): View
    {
        return view('admin.rsvps.index', [
            'pendingCount' => WeddingGuest::query()->whereNull('rsvp_status')->count(),
        ]);
    }
}
