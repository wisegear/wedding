<?php

namespace Database\Factories;

use App\Models\OrderOfServiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderOfServiceItem>
 */
class OrderOfServiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'starts_at' => fake()->time('H:i'),
        ];
    }
}
