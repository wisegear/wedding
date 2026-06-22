<?php

namespace Tests\Feature\Admin;

use App\Models\DiningOption;
use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiningManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_meal_option(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.dining.store'), [
            'course' => 'main',
            'name' => 'Beef Wellington',
            'description' => 'Served with seasonal vegetables.',
        ]);

        $response->assertRedirect(route('admin.dining.index'));

        $this->assertDatabaseHas('dining_options', [
            'course' => 'main',
            'name' => 'Beef Wellington',
            'description' => 'Served with seasonal vegetables.',
        ]);
    }

    public function test_admin_can_view_existing_meal_options(): void
    {
        $admin = User::factory()->admin()->create();

        DiningOption::query()->create([
            'course' => 'starter',
            'name' => 'Soup of the Day',
            'description' => 'Freshly prepared.',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dining.index'));

        $response->assertOk();
        $response->assertSee('Soup of the Day');
        $response->assertSee('Starter');
    }

    public function test_admin_can_delete_a_meal_option(): void
    {
        $admin = User::factory()->admin()->create();

        $option = DiningOption::query()->create([
            'course' => 'dessert',
            'name' => 'Chocolate Tart',
            'description' => 'Rich dark chocolate filling.',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.dining.destroy', $option));

        $response->assertRedirect(route('admin.dining.index'));
        $this->assertDatabaseMissing('dining_options', [
            'id' => $option->id,
        ]);
    }

    public function test_admin_can_update_a_meal_option(): void
    {
        $admin = User::factory()->admin()->create();

        $option = DiningOption::query()->create([
            'course' => 'starter',
            'name' => 'Soup of the Day',
            'description' => 'Freshly prepared.',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.dining.update', $option), [
            'course' => 'main',
            'name' => 'Roast Chicken',
            'description' => 'With vegetables.',
        ]);

        $response->assertRedirect(route('admin.dining.index'));
        $this->assertDatabaseHas('dining_options', [
            'id' => $option->id,
            'course' => 'main',
            'name' => 'Roast Chicken',
            'description' => 'With vegetables.',
        ]);
    }

    public function test_admin_can_view_guest_dining_selections_and_item_totals(): void
    {
        $admin = User::factory()->admin()->create();
        $starter = DiningOption::query()->create([
            'course' => 'starter',
            'name' => 'Soup of the Day',
            'description' => 'Freshly prepared.',
        ]);
        $main = DiningOption::query()->create([
            'course' => 'main',
            'name' => 'Roast Chicken',
            'description' => 'With vegetables.',
        ]);
        $dessert = DiningOption::query()->create([
            'course' => 'dessert',
            'name' => 'Chocolate Tart',
            'description' => 'Rich dark chocolate filling.',
        ]);
        $group = GuestGroup::query()->create([
            'group_name' => 'Wisener Family',
        ]);

        Guest::query()->create([
            'guest_group_id' => $group->id,
            'first_name' => 'Lee',
            'last_name' => 'Wisener',
            'display_name' => 'Lee Wisener',
            'dinner_choice' => [
                'starter' => $starter->id,
                'main' => $main->id,
                'dessert' => $dessert->id,
            ],
            'dietary_requirements' => 'Nut allergy',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dining.selections'));

        $response->assertOk();
        $response->assertSee('Guest selections');
        $response->assertSee('Soup of the Day');
        $response->assertSee('Roast Chicken');
        $response->assertSee('Chocolate Tart');
        $response->assertSee('Lee Wisener');
        $response->assertSee('Wisener Family');
        $response->assertSee('Nut allergy');
        $response->assertSee('1');
    }
}
