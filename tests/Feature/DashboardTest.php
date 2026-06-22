<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\GuestGroupUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_can_visit_the_dashboard(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_dashboard_auto_links_matching_existing_guest_account(): void
    {
        $guestGroup = GuestGroup::query()->create([
            'group_name' => 'Wisener Family',
        ]);
        $guest = Guest::query()->create([
            'guest_group_id' => $guestGroup->id,
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
            'display_name' => 'Lee Wisener',
        ]);
        $user = User::factory()->create([
            'name' => 'Lee Wisener',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Open RSVP form');
        $this->assertDatabaseHas('guest_group_user', [
            'guest_group_id' => $guestGroup->id,
            'guest_id' => $guest->id,
            'user_id' => $user->id,
        ]);
    }
}
