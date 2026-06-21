<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $weddingDate = CarbonImmutable::parse(
            config('wedding.date'),
            config('wedding.timezone', config('app.timezone')),
        );

        $today = now()->timezone($weddingDate->timezone)->startOfDay();
        $weddingDay = $weddingDate->startOfDay();
        $daysUntil = max(0, $today->diffInDays($weddingDay, false));

        return view('pages.home', [
            'intro' => config('wedding.intro'),
            'siteName' => config('wedding.site_name'),
            'couple' => config('wedding.couple'),
            'weddingDate' => $weddingDate,
            'venue' => config('wedding.venue'),
            'countdown' => [
                'days' => $daysUntil,
                'weeks' => intdiv($daysUntil, 7),
                'months' => max(0, $today->diffInMonths($weddingDay, false)),
            ],
        ]);
    }
}
