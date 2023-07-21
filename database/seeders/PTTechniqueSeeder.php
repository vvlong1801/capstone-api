<?php

namespace Database\Seeders;

use App\Enums\TypeTag;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PTTechniqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 4; $i++) {
            Tag::create([
                'name' => 'technique ' . $i,
                'type' => TypeTag::CreatorTechnique,
                'created_by' => 1,
            ]);
        }
    }
}
