<?php

namespace App\Services;

use App\Models\Equipment;
use App\Services\Interfaces\EquipmentServiceInterface;

class EquipmentService extends BaseService implements EquipmentServiceInterface
{
    public function getEquipments()
    {
        return Equipment::all();
    }

    public function getEquipmentsWithPagination()
    {
        return  Equipment::paginate(12);
    }

    public function createEquipment(array $payload)
    {
        \DB::beginTransaction();
        try {
            $equipment = Equipment::create(\Arr::only($payload, ['name', 'description']));
            $equipment->image()->save($payload['image']);

            if ($payload['icon']) {
                $equipment->icon()->save($payload['icon']);
            }
            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }

    public function updateEquipment($id, array $payload)
    {
        try {
            $equipment = Equipment::findOrFail($id);
            $equipment->name = \Arr::get($payload, 'name', '');
            $equipment->description = \Arr::get($payload, 'description', '');
            $equipment->save();
            if ($image = $payload['image']) {
                $equipment->image()->update($image->getAttributes());
            }
            if ($icon = \Arr::get($payload, 'icon', false)) {
                $equipment->icon()->update($icon->getAttributes());
            }
        } catch (\Throwable $th) {
            throw new \Exception("not found model", 1);
        }


        return true;
    }

    public function deleteEquipment($id)
    {
        Equipment::destroy($id);
    }
}
