<?php

namespace App\Http\Controllers;

use App\Models\DiningOption;
use App\Services\GuestAccountLinker;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly GuestAccountLinker $guestAccountLinker,
    ) {}

    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $this->guestAccountLinker->linkIfPossible($user);

        return view('dashboard', [
            'hasGuestGroupLink' => $user->guestGroups()->exists(),
            'hasDiningOptions' => DiningOption::query()->exists(),
        ]);
    }
}
