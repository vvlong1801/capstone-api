<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\ChallengePhase;
use App\Models\Message;
use App\Models\PhaseSession;
use App\Models\Plan;
use App\Models\SessionResult;
use App\Services\Interfaces\WorkoutServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkoutService extends BaseService implements WorkoutServiceInterface
{

    public function saveResultWorkoutSession($payload)
    {
        DB::beginTransaction();

        try {

            $sessionResult = new SessionResult(Arr::only($payload, [
                'plan_id', 'phase_session_id', 'calories_burned'
            ]));
            $duration = \Carbon\Carbon::parse($payload['duration'])->format('H:i:s');
            $sessionResult->duration = $duration;

            $orderPhaseSession = PhaseSession::whereId($payload["phase_session_id"])->value('order');
            $plan = Plan::whereId($payload['plan_id'])->first();
            // check complete challenge
            $total_days = ChallengePhase::where('challenge_id', $plan->challenge_id)->where('order', $plan->current_phase)->value('total_days');
            $currentSession = $plan->current_session;

            if ($currentSession == $total_days && $currentSession == $orderPhaseSession) {
                $sessionResult->plan()->update(["completed_at" => \Carbon\Carbon::now(), "current_session" => $currentSession + 1]);
                $sessionResult->save();
            } else if ($currentSession == $orderPhaseSession) {
                $sessionResult->plan()->update(["current_session" => $currentSession + 1]);
                $sessionResult->save();
            } else {
                $sessionResult->plan()->update(["updated_at" => \Carbon\Carbon::now()]);
                $sessionResult->save();
            }


            if (($feedback = Arr::get($payload, 'feedback')) != null) {
                $message = Message::create(
                    [
                        'messageable_type' => SessionResult::class,
                        'messageable_id' => $sessionResult->id,
                        'sender_id' => Auth::user()->id,
                        'receiver_id' => Challenge::whereId($sessionResult->plan->challenge_id)->value('created_by'),
                        'content' => $feedback,
                    ]
                );
            }


            DB::commit();
            return ['result' => $sessionResult, 'feedback' => $message ?? null];
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
