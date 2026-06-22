<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\GuestGroupUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestRsvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_linked_guest_can_open_the_rsvp_form(): void
    {
        [$user, $guestGroup] = $this->createLinkedGuestUser();

        $response = $this->actingAs($user)->get(route('rsvp.edit'));

        $response->assertOk();
        $response->assertSee($guestGroup->group_name);
        $response->assertSee('Lee Wisener');
        $response->assertSee('Christine Wisener');
    }

    public function test_rsvp_submission_updates_every_guest_in_the_group(): void
    {
        [$user, $guestGroup, $lee, $christine] = $this->createLinkedGuestUser();

        $response = $this->actingAs($user)->put(route('rsvp.update'), [
            'attending' => [
                $lee->id => '1',
                $christine->id => '0',
            ],
        ]);

        $response->assertRedirect(route('rsvp.edit'));
        $response->assertSessionHas('status', 'Your RSVP has been updated.');

        $this->assertDatabaseHas('guests', [
            'id' => $lee->id,
            'rsvp_status' => 'attending',
        ]);
        $this->assertDatabaseHas('guests', [
            'id' => $christine->id,
            'rsvp_status' => 'not_attending',
        ]);
    }

    public function test_unlinked_user_is_redirected_from_rsvp_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('rsvp.edit'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('status', 'Your account is not linked to a guest group yet, so RSVP is not available yet.');
    }

    public function test_rsvp_form_auto_links_matching_existing_user_before_rendering(): void
    {
        $guestGroup = GuestGroup::query()->create([
            'group_name' => 'Wisener Family',
        ]);
        Guest::query()->create([
            'guest_group_id' => $guestGroup->id,
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
            'display_name' => 'Lee Wisener',
        ]);
        $user = User::factory()->create([
            'name' => 'Lee Wisener',
        ]);

        $response = $this->actingAs($user)->get(route('rsvp.edit'));

        $response->assertOk();
        $this->assertDatabaseHas('guest_group_user', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * @return array{0: User, 1: GuestGroup, 2: Guest, 3: Guest}
     */
    private function createLinkedGuestUser(): array
    {
        $user = User::factory()->create();
        $guestGroup = GuestGroup::query()->create([
            'group_name' => 'Wisener Family',
        ]);
        $lee = Guest::query()->create([
            'guest_group_id' => $guestGroup->id,
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
            'display_name' => 'Lee Wisener',
        ]);
        $christine = Guest::query()->create([
            'guest_group_id' => $guestGroup->id,
            'first_name' => 'Christine',
            'last_name' => 'Wisener',
            'display_name' => 'Christine Wisener',
        ]);

        GuestGroupUser::query()->create([
            'guest_group_id' => $guestGroup->id,
            'guest_id' => $lee->id,
            'user_id' => $user->id,
        ]);

        return [$user, $guestGroup, $lee, $christine];
    }
}
