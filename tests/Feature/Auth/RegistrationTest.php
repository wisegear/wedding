<?php

namespace Tests\Feature\Auth;

use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\GuestGroupUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipUnlessFortifyHas(Features::registration());
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        $guest = Guest::query()->create([
            'guest_group_id' => GuestGroup::query()->create([
                'group_name' => 'Wisener Family',
            ])->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response = $this->post(route('register.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('guest_group_user', [
            'guest_group_id' => $guest->guest_group_id,
            'guest_id' => $guest->id,
            'user_id' => User::query()->where('email', 'test@example.com')->firstOrFail()->id,
        ]);
    }

    public function test_registration_is_rejected_for_name_not_on_guest_list(): void
    {
        $response = $this->from(route('register'))->post(route('register.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('register'))
            ->assertSessionHasErrors([
                'guest_lookup' => 'We could not find your name on the guest list. Please contact us if you think this is a mistake.',
            ]);

        $this->assertGuest();
    }

    public function test_registration_is_rejected_when_guest_has_already_registered(): void
    {
        $group = GuestGroup::query()->create([
            'group_name' => 'Wisener Family',
        ]);

        $guest = Guest::query()->create([
            'guest_group_id' => $group->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $user = User::factory()->create();

        GuestGroupUser::query()->create([
            'guest_group_id' => $group->id,
            'guest_id' => $guest->id,
            'user_id' => $user->id,
        ]);

        $response = $this->from(route('register'))->post(route('register.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('register'))
            ->assertSessionHasErrors([
                'guest_lookup' => 'This guest has already registered.',
            ]);
    }

    public function test_registration_name_matching_is_case_insensitive_and_trims_whitespace(): void
    {
        Guest::query()->create([
            'guest_group_id' => GuestGroup::query()->create([
                'group_name' => 'Wisener Family',
            ])->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response = $this->post(route('register.store'), [
            'first_name' => '  john ',
            'last_name' => ' DOE  ',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_allowlisted_names_can_register_without_guest_records(): void
    {
        $response = $this->post(route('register.store'), [
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
            'email' => 'lee@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Lee Wisener',
            'email' => 'lee@example.com',
        ]);
        $this->assertDatabaseCount('guest_group_user', 0);
    }
}
