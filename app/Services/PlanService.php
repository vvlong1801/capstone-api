<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\Plan;
use App\Services\Interfaces\PlanServiceInterface;
use Illuminate\Support\Facades\Auth;

class PlanService extends BaseService implements PlanServiceInterface
{
    public function getPlanChallenges()
    {
        $plan = Plan::where('user_id', Auth::user()->id)->get();
        return $plan;
    }

    public function getPlanById($id){
        $plan = Plan::find($id);
        return $plan;
    }

    public function createPlan($challengeId)
    {
        \DB::beginTransaction();
        try {
            $plan = \DB::table('plans')->insertOrIgnore([
                
                'challenge_id' => $challengeId,
                'user_id' => Auth::user()->id,
                'current_session' => 1
            ]);
            \DB::commit();
            return $plan;
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }
}
