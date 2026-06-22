<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\GuestGroup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GuestGroupController extends Controller
{
    public function index(): View
    {
        return view('admin.guest-groups.index', [
            'guestGroups' => GuestGroup::query()
                ->withCount('guests')
                ->orderBy('group_name')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.guest-groups.create', [
            'guestGroup' => new GuestGroup(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        DB::transaction(function () use ($validated): void {
            $guestGroup = GuestGroup::query()->create([
                'group_name' => $validated['group_name'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncGuests($guestGroup, $validated['guests']);
        });

        return redirect()
            ->route('admin.guest-groups.index')
            ->with('status', 'Guest group created.');
    }

    public function edit(GuestGroup $guestGroup): View
    {
        $guestGroup->load('guests');

        return view('admin.guest-groups.edit', [
            'guestGroup' => $guestGroup,
        ]);
    }

    public function update(Request $request, GuestGroup $guestGroup): RedirectResponse
    {
        $validated = $this->validatePayload($request, $guestGroup);

        DB::transaction(function () use ($guestGroup, $validated): void {
            $guestGroup->update([
                'group_name' => $validated['group_name'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncGuests($guestGroup, $validated['guests']);
        });

        return redirect()
            ->route('admin.guest-groups.edit', $guestGroup)
            ->with('status', 'Guest group updated.');
    }

    public function destroy(GuestGroup $guestGroup): RedirectResponse
    {
        $guestGroup->delete();

        return redirect()
            ->route('admin.guest-groups.index')
            ->with('status', 'Guest group deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(Request $request, ?GuestGroup $guestGroup = null): array
    {
        $validated = $request->validate([
            'group_name' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'guests' => ['required', 'array', 'min:1'],
            'guests.*.id' => ['nullable', 'integer'],
            'guests.*.first_name' => ['required', 'string', 'max:255'],
            'guests.*.last_name' => ['required', 'string', 'max:255'],
            'guests.*.notes' => ['nullable', 'string'],
        ]);

        $guests = collect($validated['guests'])
            ->map(function (array $guest): array {
                $guest['id'] = isset($guest['id']) ? (int) $guest['id'] : null;
                $guest['first_name'] = trim($guest['first_name']);
                $guest['last_name'] = trim($guest['last_name']);
                $guest['display_name'] = $this->buildDisplayName($guest['first_name'], $guest['last_name']);
                $guest['notes'] = $this->nullableTrim($guest['notes'] ?? null);

                return $guest;
            })
            ->filter(fn (array $guest) => $guest['first_name'] !== '' && $guest['last_name'] !== '')
            ->values();

        if ($guests->isEmpty()) {
            throw ValidationException::withMessages([
                'guests' => 'Add at least one guest.',
            ]);
        }

        if ($guestGroup !== null) {
            $allowedGuestIds = $guestGroup->guests()->pluck('id')->all();

            $invalidGuestId = $guests
                ->pluck('id')
                ->filter()
                ->first(fn ($id) => ! in_array($id, $allowedGuestIds, true));

            if ($invalidGuestId !== null) {
                throw ValidationException::withMessages([
                    'guests' => 'One or more guests could not be matched to this group.',
                ]);
            }
        }

        $validated['guests'] = $guests->all();

        return $validated;
    }

    /**
     * @param  array<int, array<string, mixed>>  $guestRows
     */
    private function syncGuests(GuestGroup $guestGroup, array $guestRows): void
    {
        $existingGuests = $guestGroup->guests()->get()->keyBy('id');
        $keptGuestIds = [];

        foreach ($guestRows as $guestRow) {
            $guest = isset($guestRow['id'])
                ? $existingGuests->get($guestRow['id'])
                : new Guest(['guest_group_id' => $guestGroup->id]);

            if ($guest === null) {
                continue;
            }

            $guest->fill([
                'guest_group_id' => $guestGroup->id,
                'first_name' => $guestRow['first_name'],
                'last_name' => $guestRow['last_name'],
                'display_name' => $guestRow['display_name'],
                'notes' => $guestRow['notes'] ?? null,
            ]);
            $guest->save();

            $keptGuestIds[] = $guest->id;
        }

        $guestGroup->guests()->whereNotIn('id', $keptGuestIds)->delete();
    }

    private function nullableTrim(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function buildDisplayName(string $firstName, string $lastName): string
    {
        return trim($firstName.' '.$lastName);
    }
}
