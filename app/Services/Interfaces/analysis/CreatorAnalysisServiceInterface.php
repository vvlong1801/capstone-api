<?php

namespace App\Services\Interfaces\Analysis;

interface CreatorAnalysisServiceInterface
{
    public function countMembers();
    public function countRating($creator);
    public function countSessionResults($creator);
    public function countChallenges($creator);
    public function countMemberGroupByMonth($creator);
    public function countSessionResultByMonth($creator);
    public function countMemberGroupByChallenge($creator);
    public function countSessionResultGroupByChallenge($creator);
}
