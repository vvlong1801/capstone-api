<?php

namespace App\Services\Interfaces;

interface CreatorServiceInterface
{
    public function getCreators($payload = null);
    public function createCreator($payload);
    public function getCreatorById($id);
    public function updateCreator($id, $payload);
    public function deleteCreator($id);
}
