<?php

namespace App\Services\Recommend;

use App\Enums\Gender;
use App\Models\WorkoutUser;
use App\Services\Interfaces\ChallengeRecommendServiceInterface;
use Illuminate\Support\Facades\DB;

class ChallengeRecommendService implements ChallengeRecommendServiceInterface
{
    public function recommend($userId, $challenges)
    {
        $workoutUser = WorkoutUser::with('user', 'user.goals')->where('user_id', $userId)->first();
        $genderFiltered = $challenges->whereIn('for_gender', [$workoutUser->gender, Gender::all]);
        // sort by goal

        // sort by level

        // sort by  PT

        // sort by rate

        // sort by members
        $result = $genderFiltered->mapWithKeys(function ($challenge, $key) use ($workoutUser) {
            $mappedTagIds = $challenge->tags->map(function ($tag) {
                return $tag->id;
            });
            $goalIds = $workoutUser->user->goals->map(function ($goal) {
                return $goal->id;
            });
            $weights = DB::table('goal_tag')->whereIn('goal_id', $goalIds)->whereIn('tag_id', $mappedTagIds)->pluck('weight');
            $newKey = $weights->sum();

            if ($challenge->level != null && $challenge->level->value == $workoutUser->level->value) {
                $newKey += 30;
            } else if ($challenge->level != null && $challenge->level->value < $workoutUser->level->value) {
                $newKey += 20;
            } else {
                $newKey += 10;
            }

            $newKey += $challenge->rate * 10;
            $newKey += $challenge->members_count;
            return [$newKey => $challenge];
        });

        return $result->sortKeysDesc()->values();
    }
}
