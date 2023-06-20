<?php

namespace App\Observers;

use App\Models\Challenge;
use Illuminate\Support\Facades\Auth;

class ChallengeObserver
{
    /**
     * Handle the Challenge "creating" event.
     *
     * @param  \App\Models\Challenge  $challenge
     * @return void
     */
    public function creating(Challenge $challenge)
    {
        $challenge->created_by = Auth::user()->id;
    }
}
