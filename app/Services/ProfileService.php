<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\LevelWorkoutUser;
use App\Models\Goal;
use App\Models\WorkoutUser;
use App\Services\Interfaces\ProfileServiceInterface;
use Illuminate\Support\Facades\DB;

class ProfileService extends BaseService implements ProfileServiceInterface
{
    public function getGoals()
    {
        return Goal::all();
    }

    public function getProfileByUserId($userId)
    {
        return WorkoutUser::with(['user', 'user.goals'])->where('user_id', $userId)->first();
    }

    public function updateProfile($id, $payload)
    {

        DB::beginTransaction();

        try {

            $payload['gender'] = Gender::fromName($payload['gender']);
            $payload['level'] = LevelWorkoutUser::fromName($payload['level']);
            $payload['bmi'] = round(($payload['weight'] / ($payload['height'] * $payload['height']) * 10000), 2);
            $query = WorkoutUser::where('user_id', $id);
            $query->update(\Arr::only($payload, [
                'gender', 'height', 'weight', 'bmi', 'age', 'level',
            ]));
            $query->first()->user()->update(['first_login'  => false]);
            if ($payload['phone_number']) {
                $query->first()->user()->update(['phone_number' => $payload['phone_number']]);
            }
            $query->first()->user->goals()->sync(\Arr::pluck($payload['goals'], 'id'));
            DB::commit();
            return $query->with('user', 'user.goals')->first();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
