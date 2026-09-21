<?php

namespace App\Http\Controllers;

use App\Models\OrderOfService;
use App\Models\OrderOfServiceItem;
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

        $orderOfService = OrderOfService::settings();

        return view('pages.home', [
            'orderOfService' => $orderOfService,
            'orderOfServiceItems' => OrderOfServiceItem::query()->orderBy('starts_at')->orderBy('id')->get(),
            'intro' => $orderOfService->intro,
            'guestIntro' => $orderOfService->guest_intro,
            'siteName' => config('wedding.site_name'),
            'couple' => ['partner_one' => $orderOfService->partner_one, 'partner_two' => $orderOfService->partner_two],
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
