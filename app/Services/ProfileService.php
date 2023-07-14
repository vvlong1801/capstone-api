<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\LevelWorkoutUser;
use App\Enums\TypeWorkCreator;
use App\Models\CertificateIssuer;
use App\Models\Creator;
use App\Models\Goal;
use App\Models\User;
use App\Models\WorkoutUser;
use App\Services\Interfaces\ProfileServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProfileService extends BaseService implements ProfileServiceInterface
{
    public function getGoals()
    {
        return Goal::all();
    }

    public function getCertificateIssuers()
    {
        return CertificateIssuer::all();
    }


    public function getProfileWorkoutUserByUserId($userId)
    {
        return WorkoutUser::with(['user', 'user.goals'])->where('user_id', $userId)->first();
    }

    public function getProfileCreatorByUserId($userId)
    {
        return Creator::with(['user', 'user.avatar', 'techniques', 'certificate', 'workoutTrainingMedia', 'certificateIssuer'])->where('user_id', $userId)->first();
    }

    public function updateWorkoutUserProfile($id, $payload)
    {

        DB::beginTransaction();

        try {

            $payload['gender'] = Gender::fromName($payload['gender']);
            $payload['level'] = LevelWorkoutUser::fromName($payload['level']);
            $payload['bmi'] = round(($payload['weight'] / ($payload['height'] * $payload['height']) * 10000), 2);
            $query = WorkoutUser::where('user_id', $id);
            $query->update(Arr::only($payload, [
                'gender', 'height', 'weight', 'bmi', 'age', 'level',
            ]));
            $query->first()->user()->update(['first_login'  => false]);
            if ($payload['phone_number']) {
                $query->first()->user()->update(['phone_number' => $payload['phone_number']]);
            }
            $query->first()->user->goals()->sync(Arr::pluck($payload['goals'], 'id'));
            DB::commit();
            return $query->with('user', 'user.goals')->first();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateCreatorProfile($id, $payload)
    {
        DB::beginTransaction();
        $payload['user']['first_login'] = false;
        try {
            $payload['gender'] = $payload['gender'] ? Gender::fromName($payload['gender']) : null;
            $user = User::find($id);
            if ($avatar = $payload['user']['avatar']) {
                if ($user->first_login) {
                    $user->avatar()->save($avatar);
                }
                $user->avatar()->update($avatar->getAttributes());
            }
            $user->update(Arr::only($payload['user'], ['name', 'phone_number', 'first_login']));

            $user->creator()->update(Arr::only($payload, ['age', 'gender']));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function updateFullCreatorProfile($id, $payload)
    {
        DB::beginTransaction();
        $payload['user']['first_login'] = false;
        $payload['work_type'] = TypeWorkCreator::fromName($payload['work_type']);
        $payload['certificate_issuer_id'] = $payload['certificate_issuer'];
        try {
            $payload['gender'] = $payload['gender'] ? Gender::fromName($payload['gender']) : null;
            $user = User::find($id);
            if ($avatar = $payload['user']['avatar']) {
                if ($user->first_login) {
                    $user->avatar()->save($avatar);
                }
                $user->avatar()->update($avatar->getAttributes());
            }

            if ($certificate = $payload['certificate']) {
                $user->creator->certificate()->save($certificate);
            }

            if (count($payload['workout_training_media'])) {
                $user->creator->workoutTrainingMedia()->saveMany(\Arr::where($payload['workout_training_media'], function ($img) {
                    return $img !== null;
                }));
            }

            $user->update(Arr::only($payload['user'], ['name', 'phone_number', 'first_login']));

            $user->creator()->update(Arr::only($payload, ['age', 'gender', 'address', 'facebook', 'certificate_issuer_id', 'work_type', 'desired_salary', 'introduce', 'youtube', 'zalo']));
            $user->creator->techniques()->sync($payload['techniques']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
