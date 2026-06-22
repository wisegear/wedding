<?php

namespace Tests\Feature;

use App\Models\DiningOption;
use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\GuestGroupUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestDiningTest extends TestCase
{
    use RefreshDatabase;

    public function test_linked_guest_can_open_dining_form(): void
    {
        [$user] = $this->createLinkedGuestUserWithMenu();

        $response = $this->actingAs($user)->get(route('dining.edit'));

        $response->assertOk();
        $response->assertSee('Lee Wisener');
        $response->assertSee('Starter');
        $response->assertSee('Main');
        $response->assertSee('Dessert');
    }

    public function test_dining_submission_saves_choices_and_dietary_requirements(): void
    {
        [$user, , $lee, $christine, $options] = $this->createLinkedGuestUserWithMenu();

        $response = $this->actingAs($user)->put(route('dining.update'), [
            'choices' => [
                $lee->id => [
                    'starter' => (string) $options['starter']->id,
                    'main' => (string) $options['main']->id,
                    'dessert' => (string) $options['dessert']->id,
                ],
                $christine->id => [
                    'starter' => (string) $options['starter']->id,
                    'main' => (string) $options['main']->id,
                    'dessert' => (string) $options['dessert']->id,
                ],
            ],
            'dietary_requirements' => [
                $lee->id => 'Nut allergy',
                $christine->id => '',
            ],
        ]);

        $response->assertRedirect(route('dining.edit'));
        $response->assertSessionHas('status', 'Your dining choices have been updated.');

        $this->assertDatabaseHas('guests', [
            'id' => $lee->id,
            'dietary_requirements' => 'Nut allergy',
        ]);
        $this->assertSame([
            'starter' => $options['starter']->id,
            'main' => $options['main']->id,
            'dessert' => $options['dessert']->id,
        ], $lee->fresh()->dinner_choice);
    }

    public function test_saved_dining_choices_show_summary_until_edit_mode_is_requested(): void
    {
        [$user, , $lee, $christine, $options] = $this->createLinkedGuestUserWithMenu();

        $lee->update([
            'dinner_choice' => [
                'starter' => $options['starter']->id,
                'main' => $options['main']->id,
                'dessert' => $options['dessert']->id,
            ],
            'dietary_requirements' => 'Nut allergy',
        ]);
        $christine->update([
            'dinner_choice' => [
                'starter' => $options['starter']->id,
                'main' => $options['main']->id,
                'dessert' => $options['dessert']->id,
            ],
        ]);

        $summaryResponse = $this->actingAs($user)->get(route('dining.edit'));
        $summaryResponse->assertOk();
        $summaryResponse->assertSee('Edit choices');
        $summaryResponse->assertSee('Nut allergy');

        $editResponse = $this->actingAs($user)->get(route('dining.edit', ['edit' => 1]));
        $editResponse->assertOk();
        $editResponse->assertSee('Save dining choices');
    }

    /**
     * @return array{0: User, 1: GuestGroup, 2: Guest, 3: Guest, 4: array<string, DiningOption>}
     */
    private function createLinkedGuestUserWithMenu(): array
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

        $options = [
            'starter' => DiningOption::query()->create([
                'course' => 'starter',
                'name' => 'Tomato Soup',
                'description' => 'Served warm.',
            ]),
            'main' => DiningOption::query()->create([
                'course' => 'main',
                'name' => 'Roast Chicken',
                'description' => 'With seasonal vegetables.',
            ]),
            'dessert' => DiningOption::query()->create([
                'course' => 'dessert',
                'name' => 'Chocolate Brownie',
                'description' => 'With vanilla ice cream.',
            ]),
        ];

        return [$user, $guestGroup, $lee, $christine, $options];
    }
}
