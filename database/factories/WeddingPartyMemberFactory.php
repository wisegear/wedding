<?php

namespace Database\Factories;

use App\Models\WeddingPartyMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeddingPartyMember>
 */
class WeddingPartyMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->randomElement(WeddingPartyMember::ROLES),
            'parent_side' => fn (array $attributes) => $attributes['role'] === 'Parent' ? fake()->randomElement(['Bride', 'Groom']) : null,
            'description' => fake()->sentence(),
        ];
    }
}
