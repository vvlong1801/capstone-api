<?php

namespace App\Services;

use App\Enums\StatusCreator;
use App\Models\Creator;
use App\Services\Interfaces\CreatorServiceInterface;

class CreatorService extends BaseService implements CreatorServiceInterface
{
    public function getCreators($payload = null)
    {
        $query = Creator::query();

        if ($role = \Arr::get($payload,'is_PT', null)) $query->where('role', $role);
        if ($status = \Arr::get($payload, 'status', null)) $query->where('status', $status);

        return $query->paginate(10);
    }

    public function createCreator($payload)
    {
        $creator = Creator::create([
            'user_id' => $payload['user_id'],
            'status' => StatusCreator::approved,
            'is_PT' => 0,
        ]);

        return $creator;
    }

    public function getCreatorById($id)
    {
        $creator = Creator::find($id);

        if (!$creator) throw new \Exception("not found creator");

        return $creator;
    }

    public function updateCreator($id, $payload)
    {
        $creator =  Creator::find($id);
        if (!$creator) throw new \Exception("not found creator to update");
        
        $creator->role = \Arr::get($payload, 'is_PT', $creator->is_PT);
        $creator->status = \Arr::get($payload, 'status', $creator->status);

        return $creator->update();
    }

    public function deleteCreator($id)
    {
        $creator = Creator::find($id)->delete();

        if (!$creator) throw new \Exception("not found creator to delete");
    }
}
