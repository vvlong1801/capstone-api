<?php

namespace App\Services;

use App\Enums\TypeTag;
use App\Models\Exercise;
use App\Models\Tag;
use App\Services\Interfaces\ExerciseServiceInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ExerciseService extends BaseService implements ExerciseServiceInterface
{
    public function getExercises()
    {
        return Exercise::with(['groupTags', 'createdBy', 'image'])->get();
    }

    public function getGroupTags()
    {
        return Tag::whereType(TypeTag::GroupExercise)->get();
    }

    public function getExercisesByFilters($payload, $createdBy)
    {
        // filter by group exercise
        $groupTags = \Arr::first($payload, fn ($item) => ($item['dimension'] === 'groups') && count($item['value']), false);
        $query = Exercise::whereCreatedBy($createdBy)
            ->when($groupTags, function (Builder $query, $groupTags) {
                $query->whereRelation('groupTags', function (Builder $query) use ($groupTags) {
                    $query->whereIn('name', $groupTags['value']);
                });
            });
        // filter by muscle
        $muscles = \Arr::first($payload, fn ($item) => ($item['dimension'] === 'muscles') && count($item['value']), false);
        $query = $query->when($muscles, function (Builder $query, $muscles) {
            $query->whereRelation('muscles', function (Builder $query) use ($muscles) {
                $query->whereIn('id', $muscles['value']);
            });
        });
        // filter by equipment
        $equipments = \Arr::first($payload, fn ($item) => ($item['dimension'] === 'equipments') && count($item['value']), false);
        $query = $query->when($equipments, function (Builder $query, $equipments) {
            $query->whereRelation('equipment', function (Builder $query) use ($equipments) {
                $query->whereIn('id', $equipments['value']);
            });
        });
        return $query->get();
    }

    public function getExercisesWithPagination($perPage)
    {
        return Exercise::paginate($perPage);
    }

    public function getExerciseById($id)
    {
        return Exercise::with(['groupTags', 'createdBy', 'gif', 'image', 'muscles', 'equipment'])->findOrFail($id);
    }

    public function createExercise(array $payload)
    {
        \DB::beginTransaction();
        try {
            // insert exercise info
            $exercise = Exercise::create(\Arr::only($payload, ['name', 'level', 'created_by', 'requirement_unit', 'requirement_initial', 'equipment_id', 'description', 'youtube_url']));
            $exercise->muscles()->attach($payload['muscles']);

            // insert media of exercise
            $exercise->gif()->save($payload['gif']);
            $exercise->image()->save($payload['image']);

            // resolve group tags
            // == if tag_name && type = group_exercise is existed then ignore
            // == if tag_name isn't existed then create
            $ids = Tag::createOrIgnore(TypeTag::GroupExercise, $payload['group_tags']);
            $exercise->groupTags()->attach($ids);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function updateExercise($id, array $payload)
    {
        \DB::beginTransaction();
        try {
            $exercise = Exercise::findOrFail($id);
            $exercise->muscles()->sync(\Arr::get($payload, 'muscles', []));

            $exercise->update(\Arr::only($payload, ['name', 'level', 'created_by', 'requirement_unit', 'requirement_initial', 'equipment_id', 'description', 'youtube_url']));

            if ($gif = $payload['gif']) {
                $exercise->gif()->update($gif->getAttributes());
            }
            if ($image = $payload['image']) {
                $exercise->image()->update($image->getAttributes());
            }


            $ids = Tag::createOrIgnore(TypeTag::GroupExercise, $payload['group_tags']);
            $exercise->groupTags()->sync($ids);

            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }

    public function deleteExercise($ids)
    {
        $result = Exercise::whereIn('id', $ids)->delete();
        if (!$result) throw new \Exception("not found resource to delete");
    }
}
