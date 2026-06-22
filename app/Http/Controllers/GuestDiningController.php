<?php

namespace App\Http\Controllers;

use App\Models\DiningOption;
use App\Models\GuestGroup;
use App\Services\GuestAccountLinker;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuestDiningController extends Controller
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
                ->with('status', 'Your account is not linked to a guest group yet, so dining choices are not available yet.');
        }

        $options = DiningOption::query()
            ->orderByRaw("CASE course WHEN 'starter' THEN 1 WHEN 'main' THEN 2 WHEN 'dessert' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->get()
            ->groupBy('course');

        if ($options->isEmpty()) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'Dining choices are not available yet.');
        }

        $guestGroup->load('guests');

        $isEditMode = $request->boolean('edit') || $guestGroup->guests->contains(
            fn ($guest) => ! $this->guestHasCompleteDiningChoices($guest->dinner_choice),
        );

        return view('dining.edit', [
            'guestGroup' => $guestGroup,
            'options' => $options,
            'isEditMode' => $isEditMode,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $guestGroup = $this->guestGroupForUser($request);

        if ($guestGroup === null) {
            return redirect()
                ->route('dashboard')
                ->with('status', 'Your account is not linked to a guest group yet, so dining choices are not available yet.');
        }

        $guestIds = $guestGroup->guests()->pluck('id')->all();
        $starterIds = DiningOption::query()->where('course', 'starter')->pluck('id')->all();
        $mainIds = DiningOption::query()->where('course', 'main')->pluck('id')->all();
        $dessertIds = DiningOption::query()->where('course', 'dessert')->pluck('id')->all();

        $validated = $request->validate([
            'choices' => ['required', 'array'],
            'choices.*.starter' => ['required', Rule::in($starterIds)],
            'choices.*.main' => ['required', Rule::in($mainIds)],
            'choices.*.dessert' => ['required', Rule::in($dessertIds)],
            'dietary_requirements' => ['array'],
            'dietary_requirements.*' => ['nullable', 'string'],
        ]);

        foreach ($guestIds as $guestId) {
            if (! isset($validated['choices'][$guestId])) {
                continue;
            }

            $guestGroup->guests()
                ->whereKey($guestId)
                ->update([
                    'dinner_choice' => [
                        'starter' => (int) $validated['choices'][$guestId]['starter'],
                        'main' => (int) $validated['choices'][$guestId]['main'],
                        'dessert' => (int) $validated['choices'][$guestId]['dessert'],
                    ],
                    'dietary_requirements' => $this->nullableTrim($validated['dietary_requirements'][$guestId] ?? null),
                ]);
        }

        return redirect()
            ->route('dining.edit')
            ->with('status', 'Your dining choices have been updated.');
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

    /**
     * @param  array<string, mixed>|null  $dinnerChoice
     */
    private function guestHasCompleteDiningChoices(?array $dinnerChoice): bool
    {
        return filled($dinnerChoice['starter'] ?? null)
            && filled($dinnerChoice['main'] ?? null)
            && filled($dinnerChoice['dessert'] ?? null);
    }

    private function nullableTrim(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
