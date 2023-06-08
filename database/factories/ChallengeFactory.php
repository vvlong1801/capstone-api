<?php

namespace Database\Factories;

use App\Enums\StatusChallenge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Challenge>
 */
class ChallengeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->text(10),
            'status' => StatusChallenge::init,
            'description' => fake()->text(200),
        ];
    }
}
