<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class TravelController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.hotels-and-taxis', [
            'venue' => config('wedding.venue'),
        ]);
    }
}
