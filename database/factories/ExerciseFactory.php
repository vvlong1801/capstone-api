<?php

namespace Database\Factories;

use App\Enums\EvaluateMethod;
use App\Enums\Level;
use App\Supports\Helper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
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
            'level' => Helper::randArray(Level::getNames()),
            'evaluate_method' => Helper::randArray(EvaluateMethod::getNames()),
            'description' => fake()->text(100),
        ];
    }
}
