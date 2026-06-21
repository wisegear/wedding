<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('users:set-role {email} {role}', function (string $email, string $role) {
    $user = User::query()->where('email', $email)->first();

    if (! $user) {
        $this->error("No user found for [{$email}].");

        return self::FAILURE;
    }

    if (! in_array($role, [User::ROLE_ADMIN, User::ROLE_GUEST], true)) {
        $this->error('Role must be admin or guest.');

        return self::FAILURE;
    }

    $user->forceFill(['role' => $role])->save();

    $this->info("{$user->email} now has the [{$role}] role.");

    return self::SUCCESS;
})->purpose('Assign an application role to an existing user.');
