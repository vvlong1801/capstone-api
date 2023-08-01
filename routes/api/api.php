<?php

use App\Http\Controllers\WorkoutUser\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\WorkoutUser\AnalysisController;
use App\Http\Controllers\WorkoutUser\ChallengeController;
use App\Http\Controllers\WorkoutUser\PersonalTrainerController;
use App\Http\Controllers\WorkoutUser\PlanController;
use App\Http\Controllers\WorkoutUser\ProfileController;
use App\Http\Controllers\WorkoutUser\WorkoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//=============== Auth ===============
Route::get('/test', function () {
    return 'ok';
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    //=============== Auth ===============
    Route::get('/logout', [AuthController::class, 'logout']);

    //=============== Upload file ===============
    Route::post('/upload', [MediaController::class, 'upload']);

    //=============== Personal trainer ===============
    Route::apiResource('/personal-trainers', PersonalTrainerController::class);

    //=============== Challenge ===============
    Route::post('/challenges/rate', [ChallengeController::class, 'rate']);
    Route::get('/challenges/{id}/comments', [ChallengeController::class, 'getComments']);
    Route::get('/challenges/invitations', [ChallengeController::class, 'getChallengeInvitations']);
    Route::get('/challenges/invitations/accept/{id}', [ChallengeController::class, 'acceptInvitation']);
    Route::get('/challenges/join/{id}', [ChallengeController::class, 'join']);
    Route::put('/challenges/{id}/comment', [ChallengeController::class, 'comment']);
    Route::apiResource('/challenges', ChallengeController::class);

    //=============== plan ===============
    Route::get('/plans/{id}/feedback', [PlanController::class, 'getFeedbacks']);
    Route::get('/plans/{id}/schedule', [PlanController::class, 'getSchedule']);
    Route::apiResource('/plans', PlanController::class);

    //=============== workout ===============
    Route::post('/workout/result', [WorkoutController::class, 'storeResult']);

    //=============== profile ===============
    Route::get('/goals', [ProfileController::class, 'getGoals']);
    Route::get('/analysis', [AnalysisController::class, 'index']);
    Route::put('/profile/{id}', [ProfileController::class, 'update']);
});
