<?php

namespace App\Services\Analysis;

use App\Models\Plan;
use App\Models\SessionResult;
use App\Services\BaseService;

use App\Services\Interfaces\Analysis\WorkoutUserAnalysisServiceInterface;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WorkoutUserAnalysisService extends BaseService implements WorkoutUserAnalysisServiceInterface
{
    public function trackWorkoutDay($workoutUser)
    {
        $planIds = Plan::where('user_id', $workoutUser->id)->pluck('id');
        return SessionResult::select('created_at')->where('plan_id', $planIds)->distinct()->pluck('created_at');
    }

    public function getTotalCalBurnedGroupByDay($workoutUser)
    {
        $planIds = Plan::where('user_id', $workoutUser->id)->pluck('id');
        $calInDay = SessionResult::selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") AS day, SUM(calories_burned) AS cal_sum')
            ->whereBetween('created_at', [\Carbon\Carbon::today()->subDays(7), \Carbon\Carbon::tomorrow()])
            ->whereIn('plan_id', $planIds)->groupBy('day')->orderBy('day')->get();
        return Collection::times(7, function ($index) use ($calInDay) {
            $date = \Carbon\Carbon::tomorrow()->subDays($index)->format('d-m-Y');
            $calSum = $calInDay->where(function ($item) use ($date) {
                return $item["day"] === $date;
            })->first()->cal_sum ?? 0;
            return ['day' => \Carbon\Carbon::parse($date)->dayOfWeek, 'cal_sum' => $calSum];
        })->reverse()->values();
    }

    public function getTotalWorkoutTimeGroupByDay($workoutUser)
    {
        $planIds = Plan::where('user_id', $workoutUser->id)->pluck('id');
        $timeInday = SessionResult::selectRaw('DATE_FORMAT(created_at, "%d-%m-%Y") AS day, duration')
            ->whereBetween('created_at', [\Carbon\Carbon::today()->subDays(7), \Carbon\Carbon::tomorrow()])
            ->whereIn('plan_id', $planIds)->get()->groupBy('day')->map(function ($group) {
                $totalDuration = $group->sum(function ($item) {
                    return strtotime($item['duration']) - strtotime('00:00:00');
                });
                return [
                    'day' => $group->first()['day'],
                    'total_duration' => CarbonInterval::seconds($totalDuration)->cascade()->format('%H:%I:%S'),
                ];
            });
        return Collection::times(7, function ($index) use ($timeInday) {
            $date = \Carbon\Carbon::tomorrow()->subDays($index)->format('d-m-Y');
            $timeSum = \Arr::get($timeInday->toArray(), $date.".total_duration", "00:00:00");
            return ['day' => \Carbon\Carbon::parse($date)->dayOfWeek, 'total_duration' => $timeSum];
        })->reverse()->values();
    }

    public function getTotalWorkoutTime($workoutUser)
    {
        $planIds = Plan::where('user_id', $workoutUser->id)->pluck('id');
        $totalTime = SessionResult::selectRaw('duration')
            ->where('plan_id', $planIds)->get()->sum(function ($item) {
                return strtotime($item['duration']) - strtotime('00:00:00');
            });
        return CarbonInterval::seconds($totalTime)->cascade()->format('%H:%I:%S');
    }

    public function countSession($workoutUser)
    {
        $planIds = Plan::where('user_id', $workoutUser->id)->pluck('id');
        return SessionResult::whereIn('plan_id', $planIds)->count();
    }
    public function countChallenge($workoutUser)
    {
        return Plan::where('user_id', $workoutUser->id)->count();
    }
}
