<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Goal;
use App\Models\Tag;
use App\Models\WorkoutUser;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\DB;

class RecommendSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $goalNames =  ['improve_endurance', 'lose_weight', 'increase_muscle', 'develop_sports_specific_skills', 'rehabilitation'];
        $tagNames = ['cardio', 'yoga', 'aerobic', 'kickboxing', 'hitt', 'swimming'];


        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Goal::truncate();
        Tag::truncate();
        DB::table('goal_tag')->truncate();
        DB::table('goal_user')->truncate();

        $goals = [];
        foreach ($goalNames as $name) {
            $goals[] = Goal::factory()->sequence(fn (Sequence $sequence) => [
                'name' => $name,
            ])->create();
        }

        $tags = [];
        foreach ($tagNames as $name) {
            $tags[] = Tag::factory()->sequence(fn (Sequence $sequence) => [
                'name' => $name,
            ])->create();
        }

        $users = WorkoutUser::all();

        foreach ($goals as $goal) {
            DB::table('goal_tag')->insert([
                [
                    "goal_id" => $goal->id,
                    "tag_id" => $tags[random_int(1, count($tagNames)) - 1]->id,
                    "weight" => 1
                ]
            ]);

            DB::table('goal_user')->insert([
                [
                    "goal_id" => $goal->id,
                    "user_id" => $users[random_int(1, count($users)) - 1]->user_id,
                ]
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}