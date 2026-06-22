<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Contracts\View\View;

class RsvpController extends Controller
{
    public function index(): View
    {
        $guestQuery = Guest::query()->with('group')->orderBy('last_name')->orderBy('first_name');

        return view('admin.rsvps.index', [
            'totalCount' => Guest::query()->count(),
            'attendingCount' => Guest::query()->where('rsvp_status', 'attending')->count(),
            'notAttendingCount' => Guest::query()->where('rsvp_status', 'not_attending')->count(),
            'noResponseCount' => Guest::query()->whereNull('rsvp_status')->count(),
            'guests' => $guestQuery->get(),
        ]);
    }
}
