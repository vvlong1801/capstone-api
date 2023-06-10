<?php

namespace App\Services;

use App\Models\Muscle;
use App\Services\Interfaces\MuscleServiceInterface;

class MuscleService extends BaseService implements MuscleServiceInterface {
    public function getMuscles()
    {
        return Muscle::all();
    }

    public function getMusclesWithPagination()
    {
        return  Muscle::paginate(12);
    }

    public function createMuscle(array $payload)
    {
        \DB::beginTransaction();
        try {
            $muscle = Muscle::create(\Arr::only($payload, ['name', 'description']));
            $muscle->image()->save($payload['image']);
            if ($payload['icon']) {
                $muscle->icon()->save($payload['icon']);
            }
            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }

    public function updateMuscle($id, array $payload)
    {
        try {
            $muscle = Muscle::findOrFail($id);
            $muscle->name = \Arr::get($payload, 'name', '');
            $muscle->description = \Arr::get($payload, 'description', '');
            $muscle->save();

            if ($img = $payload['image']) {
                $muscle->image()->update($img->getAttributes());
            }

            if ($icon = \Arr::get($payload, 'icon', false)) {
                $muscle->icon()->update($icon->getAtributes());
            }
        } catch (\Throwable $th) {
            throw new \Exception("not found model", 1);
        }
    }

    public function deleteMuscle($id)
    {
        Muscle::destroy($id);
    }
}
