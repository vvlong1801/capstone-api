<?php

namespace Database\Seeders;

use App\Enums\Level;
use App\Models\Exercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Exercise::truncate();

        $easyExercises = Exercise::factory()->count(100)->create();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
