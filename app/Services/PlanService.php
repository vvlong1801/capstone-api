<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\Plan;
use App\Services\Interfaces\PlanServiceInterface;
use Illuminate\Support\Facades\Auth;

class PlanService extends BaseService implements PlanServiceInterface
{
    public function getPlans()
    {
        $plans = Plan::where('user_id', Auth::user()->id);
        return $plans;
    }

    public function createPlan($challengeId)
    {
        \DB::beginTransaction();
        try {
            $challenge = Challenge::find($challengeId);
            $plan = \DB::table('plans')->insertOrIgnore([
                'challenge_phase_id' => $challenge->phases()->first()->id,
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
