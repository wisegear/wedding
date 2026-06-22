<?php

namespace App\Http\Controllers;

use App\Models\GuestGroup;
use App\Services\GuestAccountLinker;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GuestRsvpController extends Controller
{
    public function __construct(
        private readonly GuestAccountLinker $guestAccountLinker,
    ) {}

    public function edit(Request $request): View|RedirectResponse
    {
        $guestGroup = $this->guestGroupForUser($request);

        if ($guestGroup === null) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'Your account is not linked to a guest group yet, so RSVP is not available yet.');
        }

        $guestGroup->load('guests');

        return view('rsvp.edit', [
            'guestGroup' => $guestGroup,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $guestGroup = $this->guestGroupForUser($request);

        if ($guestGroup === null) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'Your account is not linked to a guest group yet, so RSVP is not available yet.');
        }

        $guestIds = $guestGroup->guests()->pluck('id')->all();

        $validated = $request->validate([
            'attending' => ['array'],
            'attending.*' => ['nullable', 'boolean'],
        ]);

        $attendingIds = collect($validated['attending'] ?? [])
            ->filter(fn ($value) => (bool) $value)
            ->keys()
            ->map(fn ($guestId) => (int) $guestId)
            ->all();

        foreach ($guestIds as $guestId) {
            $guestGroup->guests()
                ->whereKey($guestId)
                ->update([
                    'rsvp_status' => in_array($guestId, $attendingIds, true) ? 'attending' : 'not_attending',
                ]);
        }

        return redirect()
            ->route('rsvp.edit')
            ->with('status', 'Your RSVP has been updated.');
    }

    private function guestGroupForUser(Request $request): ?GuestGroup
    {
        $user = $request->user();

        $this->guestAccountLinker->linkIfPossible($user);

        return $user
            ->guestGroups()
            ->with('guests')
            ->first();
    }
}
