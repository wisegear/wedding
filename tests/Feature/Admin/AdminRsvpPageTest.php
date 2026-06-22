<?php

namespace Tests\Feature\Admin;

use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRsvpPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_rsvp_page_shows_summary_counts_and_guest_statuses(): void
    {
        $admin = User::factory()->admin()->create();
        $group = GuestGroup::query()->create([
            'group_name' => 'Wisener Family',
        ]);

        Guest::query()->create([
            'guest_group_id' => $group->id,
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
            'display_name' => 'Lee Wisener',
            'rsvp_status' => 'attending',
        ]);
        Guest::query()->create([
            'guest_group_id' => $group->id,
            'first_name' => 'Christine',
            'last_name' => 'Wisener',
            'display_name' => 'Christine Wisener',
            'rsvp_status' => 'not_attending',
        ]);
        Guest::query()->create([
            'guest_group_id' => $group->id,
            'first_name' => 'Martin',
            'last_name' => 'Wisener',
            'display_name' => 'Martin Wisener',
            'rsvp_status' => null,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.rsvps.index'));

        $response->assertOk();
        $response->assertSee('Total Guests');
        $response->assertSee('Attending');
        $response->assertSee('Not Attending');
        $response->assertSee('No Response');
        $response->assertSee('Lee Wisener');
        $response->assertSee('Christine Wisener');
        $response->assertSee('Martin Wisener');
        $response->assertSee('Wisener Family');
        $response->assertSee('3');
        $response->assertSee('1');
    }
}
