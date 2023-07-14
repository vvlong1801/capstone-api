<?php

namespace App\Services\Interfaces;

interface TagServiceInterface
{
    public function getTags();
    public function createTag(array $payload);
    public function updateTag($id, array $payload);
    public function deleteTag($id);
}
