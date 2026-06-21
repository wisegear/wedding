<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeddingGuest;
use Illuminate\Contracts\View\View;

class GuestController extends Controller
{
    public function index(): View
    {
        return view('admin.guests.index', [
            'guestCount' => WeddingGuest::query()->count(),
        ]);
    }
}
