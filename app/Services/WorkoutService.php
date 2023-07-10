<?php

namespace App\Services;

use App\Models\PhaseSession;
use App\Models\ResultSession;
use App\Services\Interfaces\WorkoutServiceInterface;
use Illuminate\Support\Facades\DB;

class WorkoutService extends BaseService implements WorkoutServiceInterface
{

    public function saveResultWorkoutSession($payload)
    {
        DB::beginTransaction();

        try {

            $planSession = new ResultSession(\Arr::only($payload, [
                'plan_id', 'phase_session_id', 'calories_burned'
            ]));
            $duration = \Carbon\Carbon::parse($payload['duration'])->format('H:i:s');
            $planSession->duration = $duration;
            $orderPhaseSession = PhaseSession::whereId($payload["phase_session_id"])->value('order');
            $currentSession = $planSession->plan->current_session;

            // check complete challenge
            if ($currentSession + 1 == $planSession->plan->challenge->total_days) {
                $planSession->plan()->update(["completed_at" => \Carbon\Carbon::now()]);
            } else if ($currentSession == $orderPhaseSession) {
                $planSession->plan()->update(["current_session" => $currentSession + 1]);
            }

            $planSession->save();

            DB::commit();
            return $planSession;
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
