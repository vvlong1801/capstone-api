<?php

namespace App\Services;


use App\Models\Plan;

use App\Models\ResultSession;
use App\Services\Interfaces\PlanServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanService extends BaseService implements PlanServiceInterface
{
    public function getPlanChallenges()
    {
        $plan = Plan::where('user_id', Auth::user()->id)->orderByDesc("updated_at")->get();
        return $plan;
    }

    public function getPlanById($id)
    {
        $plan = Plan::find($id);
        return $plan;
    }

    public function createPlan($challengeId)
    {
        DB::beginTransaction();
        try {
            $plan = DB::table('plans')->insertOrIgnore([
                'challenge_id' => $challengeId,
                'user_id' => Auth::user()->id,
                'current_session' => 1
            ]);
            DB::commit();
            return $plan;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function createPlanSession($payload)
    {
        DB::beginTransaction();

        try {

            $planSession = new ResultSession(\Arr::only($payload, [
                'plan_id', 'phase_session_id', 'calories_burned'
            ]));
            $duration = \Carbon\Carbon::parse($payload['duration'])->format('H:i:s');
            $planSession->duration = $duration;
            $planSession->plan()->update(["current_session" => $planSession->plan->current_session + 1]);
            $planSession->save();

            DB::commit();
            return $planSession;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
