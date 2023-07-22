<?php

namespace Database\Seeders;

use App\Enums\TypeTag;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PTTechniqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Tag::truncate();

        for ($i = 1; $i < 4; $i++) {
            Tag::create([
                'name' => 'technique ' . $i,
                'type' => TypeTag::CreatorTechnique,
                'created_by' => 1,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
