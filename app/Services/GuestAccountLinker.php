<?php

namespace App\Services;

use App\Models\GuestGroupUser;
use App\Models\User;
use Illuminate\Support\Str;

class GuestAccountLinker
{
    public function __construct(
        private readonly InvitedGuestLookup $invitedGuestLookup,
    ) {}

    public function linkIfPossible(User $user): ?GuestGroupUser
    {
        $existingLink = $user->guestLinks()->first();

        if ($existingLink !== null) {
            return $existingLink;
        }

        [$firstName, $lastName] = $this->extractNameParts($user->name);

        if ($firstName === null || $lastName === null) {
            return null;
        }

        $guest = $this->invitedGuestLookup->findByName($firstName, $lastName);

        if ($guest === null || $guest->registrationLinks()->exists()) {
            return null;
        }

        return GuestGroupUser::query()->create([
            'guest_group_id' => $guest->guest_group_id,
            'guest_id' => $guest->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @return array{0: string|null, 1: string|null}
     */
    private function extractNameParts(string $name): array
    {
        $parts = Str::of($name)
            ->squish()
            ->explode(' ')
            ->filter()
            ->values();

        if ($parts->count() < 2) {
            return [null, null];
        }

        return [
            $parts->first(),
            $parts->slice(1)->implode(' '),
        ];
    }
}
