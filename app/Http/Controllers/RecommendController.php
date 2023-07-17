<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\RecommendServiceInterface;
use App\Http\Resources\RecommendResource;
use Illuminate\Http\Request;

class RecommendController extends Controller
{
    protected $recommendService;
    public function __construct(RecommendServiceInterface $recommendService)
    {
        $this->recommendService = $recommendService;
    }

    /**
     * Display a listing of the resource.
     */
    public function recommend(int $userId)
    {
        $recommends = $this->recommendService->recommendChallenges('goal', [1,2,3]);
        return $this->responseOk(RecommendResource::collection($recommends));
    }

}
