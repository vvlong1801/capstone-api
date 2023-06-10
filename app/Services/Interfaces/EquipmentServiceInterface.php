<?php

namespace App\Services\Interfaces;

interface EquipmentServiceInterface
{
    public function getEquipments();
    public function getEquipmentsWithPagination();
    // public function findById(int $id);
    public function createEquipment(array $payload);
    public function updateEquipment($id, array $payload);
    public function deleteEquipment($id);
}
