<?php

namespace App\Services;

use App\Services\Interfaces\RecommendServiceInterface;
use App\Models\Tag;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class RecommendService extends BaseService implements RecommendServiceInterface
{
    public function recommendChallenges($type, $ids)
    { 
        $rs = $this->reSortArray($ids);  
        $tagIds = array_column($rs, 'tag_id');
        $goals = Goal::whereIn('id', $ids)->pluck('name', 'id');
        $tags = Tag::whereIn('id', $tagIds)->where('type', 1)->pluck('name', 'id');

        foreach ($rs as &$item) {
            $goalId = $item['goal_id'];
            $tagId = $item['tag_id'];
            $item['goal_name'] = $goals[$goalId] ?? null;
            $item['tag_name'] = $tags[$tagId] ?? null;
        };

        return $rs;
    }

    private function reSortArray($ids) {
        $arr = DB::table('goal_tag')
                    ->whereIn('goal_id', $ids)
                    ->orderBy('goal_id', 'asc')
                    ->select('goal_id', 'tag_id', 'weight')
                    ->get();

        $tempArray = [];
        foreach ($arr as $item) {
            $tagId = $item->tag_id;
            $weight = $item->weight;
            if (!isset($tempArray[$tagId]) || $weight > $tempArray[$tagId]['weight']) {
                $tempArray[$tagId] = [
                    'goal_id' => $item->goal_id,
                    'tag_id' => $tagId,
                    'weight' => $weight
                ];
            }
        }

        return array_values($tempArray);
    }
}
