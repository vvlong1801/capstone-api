<?php

namespace App\Services;

use App\Models\Tag;
use App\Services\Interfaces\TagServiceInterface;

class TagService extends BaseService implements TagServiceInterface
{
    public function getTags()
    {
        return Tag::all();
    }
    public function createTag(array $payload)
    {
    }
    public function updateTag($id, array $payload)
    {
    }
    public function deleteTag($id)
    {
    }
}
