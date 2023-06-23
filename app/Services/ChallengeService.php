<?php

namespace App\Services;

use App\Enums\StatusChallenge;
use App\Models\Challenge;
use App\Models\ChallengePhase;
use App\Models\ExerciseRequirement;
use App\Models\SessionExercise;
use App\Services\Interfaces\ChallengeServiceInterface;

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
            $challenge = new Challenge(\Arr::only($payload, [
                'name', 'description', 'sort_desc', 'max_members',
                'sort_desc', 'accept_all', 'public',
                'start_at', 'finish_at', 'created_by'
            ]));

            $challenge->status = StatusChallenge::init;
            $challenge->save();

            // save media
            $challenge->image()->save($payload['image']);

            // save template
            $phases = \Arr::get($payload, 'template.phases', []);
            $this->createChallengePhase($challenge, $phases);

            // save invitation

            // send notification to admin

            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw $th;
        }
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
            dd($session);
            //insert session
            $newSession = $phase->sessions()->create(['name' => 'day ' . $index, 'order' => $index]);
            // insert exercises
            foreach ($session as $idx => $exercise) {

                $ssExercise = SessionExercise::create([
                    'exercise_id' => $exercise['exercise_id'],
                    'phase_session_id' => $newSession->id,
                    'requirement' => $exercise['requirement'],
                    'requirement_unit' => $exercise['requirement_unit'],
                    'order' => $idx,
                    'alternative_exercise' => false,
                ]);

                $requires = collect([]);
                //insert requirements
                foreach ($exercise['requirement'] as $k => $req) {
                    $param = $req['param'];
                    $data = \Arr::where(config('constant.param_requirement'), fn ($value, $key) => $key === $param);

                    $newReq = new ExerciseRequirement([
                        'param' => $param,
                        'param_type' => $data[$param]['type'],
                        'unit' => $data[$param]['unit'],
                        'value' => $req['value'],
                        'order' => $k,
                    ]);
                    $requires->push($newReq);
                }

                $ssExercise->requirements()->createMany($requires->toArray());
            }
        }
    }

    public function updateChallenge($id, array $payload)
    {
        $challenge = Challenge::find($id);
        $payload['type_id'] = \Arr::get($payload, 'type', 0);
        $challenge->update(\Arr::only($payload, ['name', 'type_id', 'description']));

        if ($payload['image']) $challenge->image()->update($payload['image']->getAttributes());
        $challenge->exercises()->sync(\Arr::get($payload, 'exercises', []));

        return $challenge->loadCount('exercises');
    }

    public function deleteChallenge($id)
    {
        $result = Challenge::where('id', $id)->delete();
        if (!$result) throw new \Exception("delete is error");
    }
}
