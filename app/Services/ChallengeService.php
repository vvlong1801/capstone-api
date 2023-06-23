<?php

namespace App\Services;

use App\Enums\RoleChallenge;
use App\Enums\StatusChallenge;
use App\Enums\TypeTag;
use App\Models\Challenge;
use App\Models\ChallengePhase;
use App\Models\SessionExercise;
use App\Models\Tag;
use App\Services\Interfaces\ChallengeServiceInterface;
use Illuminate\Support\Facades\Date;

class ChallengeService extends BaseService implements ChallengeServiceInterface
{
    public function getChallenges()
    {
        return Challenge::with(['image', 'createdBy'])->get();
    }

    public function getChallengeById($id)
    {
        $challenge = Challenge::with(['createdBy', 'image', 'phases', 'phases.sessions', 'phases.sessions.sessionExercises'])->whereId($id)->first();
        return $challenge;
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
                'created_by', 'start_at', 'finish_at',
            ]));

            $challenge->status = StatusChallenge::init;
            $challenge->save();

            // save media
            $challenge->image()->save($payload['image']);

            //save tags
            $ids = Tag::createOrIgnore(TypeTag::ChallengeTag, $payload['tags']);
            $challenge->tags()->attach($ids);

            // save template
            $phases = \Arr::get($payload, 'template.phases', []);
            $this->createChallengePhase($challenge, $phases);

            // save invitation
            $payload['invitation'] = \Arr::map($payload['invitation'], function ($item) {
                $item['role'] = RoleChallenge::fromName(\Str::lower($item['role']));
                $item['expires_at'] = Date::now()->addDay(3);
                return $item;
            });
            $challenge->invitations()->createMany($payload['invitation']);
            dd('aaaa');
            // send notification to admin

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
}
