<?php

namespace App\Services;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class InvitedGuestLookup
{
    /**
     * @var array<int, string>
     */
    private const NAME_ALLOWLIST = [
        'lee wisener',
        'lyndsey herkes',
        'martin wisener',
        'christine wisener',
    ];

    public function findByName(string $firstName, string $lastName): ?Guest
    {
        $normalizedFirstName = Str::lower(trim($firstName));
        $normalizedLastName = Str::lower(trim($lastName));

        return Guest::query()
            ->where(function (Builder $query) use ($normalizedFirstName): void {
                $query->whereRaw('LOWER(first_name) = ?', [$normalizedFirstName]);
            })
            ->where(function (Builder $query) use ($normalizedLastName): void {
                $query->whereRaw('LOWER(last_name) = ?', [$normalizedLastName]);
            })
            ->first();
    }

    public function isAllowedWithoutGuestRecord(string $firstName, string $lastName): bool
    {
        return in_array($this->normalizeFullName($firstName, $lastName), self::NAME_ALLOWLIST, true);
    }

    private function normalizeFullName(string $firstName, string $lastName): string
    {
        return Str::of($firstName)
            ->trim()
            ->append(' ')
            ->append($lastName)
            ->squish()
            ->lower()
            ->value();
    }
}
