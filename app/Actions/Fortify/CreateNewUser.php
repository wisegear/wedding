<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\GuestGroupUser;
use App\Models\User;
use App\Services\InvitedGuestLookup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function __construct(
        private readonly InvitedGuestLookup $invitedGuestLookup,
    ) {}

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => $this->nameRules(),
            'last_name' => $this->nameRules(),
            'email' => $this->emailRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $firstName = trim($input['first_name']);
        $lastName = trim($input['last_name']);

        $guest = $this->invitedGuestLookup->findByName($firstName, $lastName);
        $isAllowlisted = $this->invitedGuestLookup->isAllowedWithoutGuestRecord($firstName, $lastName);

        if ($guest === null && ! $isAllowlisted) {
            throw ValidationException::withMessages([
                'guest_lookup' => 'We could not find your name on the guest list. Please contact us if you think this is a mistake.',
            ]);
        }

        if ($guest !== null && $guest->registrationLinks()->exists()) {
            throw ValidationException::withMessages([
                'guest_lookup' => 'This guest has already registered.',
            ]);
        }

        return DB::transaction(function () use ($input, $firstName, $lastName, $guest): User {
            $user = User::create([
                'name' => $firstName.' '.$lastName,
                'email' => $input['email'],
                'password' => $input['password'],
                'role' => User::ROLE_GUEST,
            ]);

            if ($guest !== null) {
                GuestGroupUser::query()->create([
                    'guest_group_id' => $guest->guest_group_id,
                    'guest_id' => $guest->id,
                    'user_id' => $user->id,
                ]);
            }

            return $user;
        });
    }
}
