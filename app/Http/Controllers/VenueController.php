<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class VenueController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.venue', [
            'venue' => config('wedding.venue'),
        ]);
    }
}
