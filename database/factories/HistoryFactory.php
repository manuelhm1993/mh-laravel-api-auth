<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History>
 */
class HistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => User::all()->random()->id,
            'country'     => $this->faker->country(),
            'city'        => $this->faker->city(),
            'weather'     => $this->faker->word(),
            'temperature' => $this->faker->numberBetween(-30, 50),
            'created_at'  => now(),
            'updated_at'  => now(),
        ];
    }
}
