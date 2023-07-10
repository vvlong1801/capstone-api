<?php

namespace App\Services;

use App\Enums\StatusChallenge;
use App\Enums\TypeTag;
use App\Models\Challenge;
use App\Models\ChallengePhase;
use App\Models\Plan;
use App\Models\SessionExercise;
use App\Models\Tag;
use App\Services\Interfaces\ChallengeServiceInterface;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;

class ChallengeService extends BaseService implements ChallengeServiceInterface
{
    public function getChallenges()
    {
        $user = Auth::user();
        $challenge = Challenge::with(['mainImage', 'createdBy', 'tags'])
            ->withCount(['phases'])
            ->withSum('phases as total_sessions', 'total_days');
        if ($user->hasAdminPermissions) {
            return $challenge->get();
        } else if ($user->isCreator) {
            return $challenge->where("created_by", $user->id)->get();
        } else {
            $userChallenges = Plan::where("user_id", $user->id)->pluck("challenge_id");
            $result = $challenge->whereNotIn('id', $userChallenges)->where('status', StatusChallenge::running)->get();
            return $result;
        }
    }

    public function getChallengeById($id)
    {
        $challenge = Challenge::with(['createdBy', 'images', 'mainImage'])
            ->withCount(['phases'])
            ->withSum('phases as total_sessions', 'total_days')
            ->whereId($id)
            ->first();
        return $challenge;
    }

    public function getChallengeTemplateById($id)
    {
        $template = ChallengePhase::where('challenge_id', $id)->with(['sessions', 'sessions.sessionExercises', 'sessions.sessionExercises.exercise'])->get();

        return $template;
    }

    public function createChallengeMember($id)
    {
        // check accept rule of challenge
        \DB::beginTransaction();

        try {
            $challenge = Challenge::find($id);
            $challenge->members()->syncWithoutDetaching([Auth::user()->id, ['status' => $challenge->accept_all]]);
            \DB::commit();
            return $challenge->accept_all;
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }

    // public function getChallengeStatistics($id)
    // {
    // }

    // public function getChallengeFeedbacks($id)
    // {
    // }

    public function getChallengeTags()
    {
        return Tag::whereType(TypeTag::ChallengeTag)->get();
    }

    public function confirmNewChallenge($id, $payload)
    {
        // change status
        Challenge::whereId($id)
            ->where('status', StatusChallenge::init)->when($payload['approve'], function (QueryBuilder $query) {
                $query->update(['approved_at' => now(), 'status' => StatusChallenge::waiting]);
                $query->where('start_at', '<', now())
                    ->where('finish_at', '>', now())
                    ->update(['status', StatusChallenge::running]);
            }, function (QueryBuilder $query) {
                $query->update(['status' => StatusChallenge::cancel]);
            });

        //approve = true
        //==notify creator

        //==send invitation

    }

    public function approveChallenge($id)
    {
        $now = \Carbon\Carbon::now();
        \DB::beginTransaction();
        try {
            Challenge::whereId($id)->where('status', StatusChallenge::init)->update(['approved_at' => $now, 'status' => StatusChallenge::running]);
            // $query->whereDate('start_at', '>', $now)->update(['approved_at' => $now, 'status' => StatusChallenge::waiting]);
            // $query->whereDate('start_at', '<', $now)->whereDate('finish_at', '>', $now)->update(['approved_at' => $now, 'status' => StatusChallenge::running]);
            \DB::commit();
            return Challenge::find($id);
        } catch (\Throwable $th) {
            \DB::rollback();
        }
    }

    public function createChallenge(array $payload)
    {

        \DB::beginTransaction();
        try {
            // init challenge
            $payload['start_at'] = \Carbon\Carbon::parse($payload['start_at'])->toDateTimeString();
            $payload['finish_at'] = \Carbon\Carbon::parse($payload['finish_at'])->toDateTimeString();

            $challenge = new Challenge(\Arr::only($payload, [
                'name', 'description', 'sort_desc', 'max_members',
                'sort_desc', 'accept_all', 'public',
                'created_by', 'start_at', 'finish_at', 'youtube_url'
            ]));

            $challenge->status = StatusChallenge::init;
            $challenge->save();

            // save media
            $challenge->images()->saveMany($payload['images']);
            //save tags
            $ids = Tag::createOrIgnore(TypeTag::ChallengeTag, $payload['tags']);
            $challenge->tags()->attach($ids);

            // save template
            $phases = \Arr::get($payload, 'template.phases', []);
            $this->createChallengePhase($challenge, $phases);

            // // save invitation
            // $payload['invitation'] = \Arr::map($payload['invitation'], function ($item) {
            //     $item['role'] = RoleChallenge::fromName(\Str::lower($item['role']));
            //     $item['expires_at'] = Date::now()->addDay(3);
            //     return $item;
            // });
            // $challenge->invitations()->createMany($payload['invitation']);
            \DB::commit();
            return $challenge;
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }

    // public function createChallengeInvitation($challenge, $payload)
    // {
    //     \DB::beginTransaction();
    //     try {
    //         $invitations = \Arr::map($payload, function ($item) {
    //             $item['role'] = RoleChallenge::fromName(\Str::lower($item['role']));
    //             $item['expires_at'] = Date::now()->addDay(3);
    //             return $item;
    //         });
    //         $result = $challenge->invitations()->createMany($invitations);
    //         \DB::commit();
    //     } catch (\Throwable $th) {
    //         \DB::rollback();
    //         throw $th;
    //     }
    // }

    private function createChallengePhase(Challenge $challenge, array $payload)
    {
        //check existed phase
        if (!count($payload)) throw new \Exception("challenge hasn't phases", 1);

        foreach ($payload as $index => $data) {
            // init phase
            $newPhase = new ChallengePhase(\Arr::only($data, [
                'name', 'total_days', 'note',
            ]));
            $newPhase->order = $index;
            $challenge->phases()->save($newPhase);

            // calculate level
            // add sessions
            $this->createSessions($newPhase, $data['sessions']);
        }
    }

    private function createSessions(ChallengePhase $phase, $sessions)
    {
        // check existed session
        if (!count($sessions)) throw new \Exception("the phase hasn't sessions", 1);

        foreach ($sessions as $index => $session) {
            //insert session
            $newSession = $phase->sessions()->create(['name' => 'day ' . ($index + 1), 'order' => ($index + 1)]);
            // insert exercises
            foreach ($session['exercises'] as $idx => $exercise) {
                // dd($newSession);
                $ssExercise = SessionExercise::create([
                    'exercise_id' => $exercise['exercise_id'],
                    'phase_session_id' => $newSession->id,
                    'requirement' => $exercise['requirement'],
                    'requirement_unit' => $exercise['requirement_unit'],
                    'order' => $idx,
                    'alternative_exercise' => false,
                ]);
            }
        }
    }

    public function updateChallengeInformation($id, $payload)
    {
        \DB::beginTransaction();
        try {
            // init challenge
            $payload['start_at'] = \Carbon\Carbon::parse($payload['start_at'])->toDateTimeString();
            $payload['finish_at'] = \Carbon\Carbon::parse($payload['finish_at'])->toDateTimeString();

            $challenge = Challenge::find($id);
            $challenge->fill(\Arr::only($payload, [
                'name', 'description', 'sort_desc', 'max_members',
                'sort_desc', 'accept_all', 'public',
                'start_at', 'finish_at', 'youtube_url'
            ]));
            $challenge->save();

            // save media
            $challenge->images()->saveMany(\Arr::where($payload['images'], function ($img) {
                return $img !== null;
            }));

            //save tags
            $ids = Tag::createOrIgnore(TypeTag::ChallengeTag, $payload['tags']);
            $challenge->tags()->sync($ids);

            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
    }

    public function deleteChallenge($id)
    {
        $result = Challenge::where('id', $id)->delete();
        if (!$result) throw new \Exception("delete is error");
    }
}
