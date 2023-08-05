<?php

namespace App\Http\Controllers\Admin\sources;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGoalRequest;
use App\Http\Resources\Admin\GoalResource;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goals = Goal::with('tags')->get();
        return $this->responseOk(GoalResource::collection($goals), 'get goals success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoalRequest $request)
    {
        $payload = $request->validated();
        try {
            DB::beginTransaction();
            $newGoal = Goal::create(["name" => $payload["name"]]);
            $attachData = \Arr::mapWithKeys($payload['tags'], function ($value, string $key) {
                return [$value["id"] => ['weight' => intval($value["weight"])]];
            });
            $newGoal->tags()->attach($attachData);
            DB::commit();
            return $this->responseNoContent("create goal success");
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, "can't create goal");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Goal::destroy($id);
        return $this->responseNoContent('goal has been deleted');
    }
}
