<?php

namespace Database\Seeders;

use App\Enums\Level;
use App\Models\Challenge;
use App\Models\ChallengePhase;
use App\Models\ChallengeType;
use App\Models\Exercise;
use App\Models\SessionExercise;
use App\Models\WorkoutSession;
use App\Supports\Helper;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $challenges = Challenge::factory()->count(3)
            ->sequence(fn (Sequence $sequence) => [
                'created_by' => $sequence->index + 1
            ])->create();
        foreach ($challenges as $key => $challenge) {
            $exercises = Exercise::factory()->count(20)->state(['created_by' => ($key + 1)])->create();
            $phases = ChallengePhase::factory()->for($challenge)->create();
            $workoutSessions = WorkoutSession::factory()
                ->count(7)->for($phases)
                ->sequence(fn (Sequence $sequence) => [
                    'order' => $sequence->index
                ])
                ->has(SessionExercise::factory()->count(5)->sequence(fn (Sequence $sequence) => [
                    'order' => $sequence->index,
                    'exercise_id' => $exercises->pluck('id')->random(1)->first()
                ]), 'exercises')
                ->create();
        }
    }
}
