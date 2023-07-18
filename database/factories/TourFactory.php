<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $travelId = Travel::pluck('id')->toArray();

        return [
            'name' => fake()->text('10'),
            'starting_date' => fake()->date(),
            'ending_date' => fake()->date(),
            'price' => fake()->numberBetween(299, 999),
            'travel_id' => fake()->randomElement($travelId),
        ];
    }
}
