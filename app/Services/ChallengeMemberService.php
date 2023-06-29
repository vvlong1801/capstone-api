<?php

namespace App\Services;

use App\Models\Challenge;
use App\Services\Interfaces\ChallengeMemberServiceInterface;
use Illuminate\Support\Facades\Auth;

class ChallengeMemberService extends BaseService implements ChallengeMemberServiceInterface
{
    public function createChallengeMember($id){
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
}
