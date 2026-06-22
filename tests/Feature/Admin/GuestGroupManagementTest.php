<?php

namespace Tests\Feature\Admin;

use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestGroupManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_guest_group_with_multiple_guests(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.guest-groups.store'), [
            'group_name' => 'Wisener Family',
            'notes' => 'Front row',
            'guests' => [
                [
                    'first_name' => 'Lee',
                    'last_name' => 'Wisener',
                    'notes' => 'Best man family',
                ],
                [
                    'first_name' => 'Christine',
                    'last_name' => 'Wisener',
                    'notes' => '',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.guest-groups.index'));

        $guestGroup = GuestGroup::query()->firstOrFail();

        $this->assertDatabaseHas('guest_groups', [
            'id' => $guestGroup->id,
            'group_name' => 'Wisener Family',
            'notes' => 'Front row',
        ]);
        $this->assertDatabaseCount('guests', 2);
        $this->assertSame(2, $guestGroup->guests()->count());
        $this->assertDatabaseHas('guests', [
            'guest_group_id' => $guestGroup->id,
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
            'display_name' => 'Lee Wisener',
        ]);
    }

    public function test_admin_can_edit_a_group_and_add_or_remove_guests(): void
    {
        $admin = User::factory()->admin()->create();
        $group = GuestGroup::query()->create(['group_name' => 'Wisener Family']);
        $lee = Guest::query()->create([
            'guest_group_id' => $group->id,
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
        ]);
        $christine = Guest::query()->create([
            'guest_group_id' => $group->id,
            'first_name' => 'Christine',
            'last_name' => 'Wisener',
            'email' => 'christine@example.com',
            'phone' => '555-0101',
        ]);
        $response = $this->actingAs($admin)->patch(route('admin.guest-groups.update', $group), [
            'group_name' => 'Wisener Household',
            'notes' => 'Updated notes',
            'guests' => [
                [
                    'id' => $christine->id,
                    'first_name' => 'Christine',
                    'last_name' => 'Wisener',
                    'notes' => '',
                ],
                [
                    'first_name' => 'Another',
                    'last_name' => 'Guest',
                    'notes' => 'Added later',
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.guest-groups.edit', $group));

        $group->refresh();

        $this->assertSame('Wisener Household', $group->group_name);
        $this->assertDatabaseMissing('guests', ['id' => $lee->id]);
        $this->assertDatabaseHas('guests', [
            'id' => $christine->id,
            'display_name' => 'Christine Wisener',
            'email' => 'christine@example.com',
            'phone' => '555-0101',
        ]);
        $this->assertDatabaseHas('guests', [
            'guest_group_id' => $group->id,
            'first_name' => 'Another',
            'last_name' => 'Guest',
        ]);
    }

    public function test_deleting_a_group_deletes_its_guests(): void
    {
        $admin = User::factory()->admin()->create();
        $group = GuestGroup::query()->create(['group_name' => 'Wisener Family']);
        $guest = Guest::query()->create([
            'guest_group_id' => $group->id,
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.guest-groups.destroy', $group));

        $response->assertRedirect(route('admin.guest-groups.index'));
        $this->assertDatabaseMissing('guest_groups', ['id' => $group->id]);
        $this->assertDatabaseMissing('guests', ['id' => $guest->id]);
    }

    public function test_non_admin_users_cannot_access_guest_group_routes(): void
    {
        $guestUser = User::factory()->create();

        $response = $this->actingAs($guestUser)->get(route('admin.guest-groups.index'));

        $response->assertForbidden();
    }
}
