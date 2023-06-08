<?php

namespace Database\Factories;

use App\Enums\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChallengePhase>
 */
class ChallengePhaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'level' => Level::easy->name,
            'order' => 1,
            'count_sessions' => 7,
            'active_days' => 6,
            'rest_days' => 1,
        ];
    }
}
