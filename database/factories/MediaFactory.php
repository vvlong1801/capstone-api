<?php

namespace Database\Factories;

use App\Enums\CommonStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'collection_name' => 'tests',
            'type' => 0,
            'disk' => 0,
            'name' => fake()->text(10),
            'path' => fake()->url(),
            'status' => CommonStatus::active,
        ];
    }
}
