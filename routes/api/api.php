<?php

use App\Http\Controllers\WorkoutUser\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\WorkoutUser\ChallengeController;
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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    //=============== Auth ===============
    Route::get('/logout', [AuthController::class, 'logout']);

    //=============== Upload file ===============
    Route::post('/upload', [MediaController::class, 'upload']);

    //=============== Challenge ===============
    Route::get('/challenges/invitations', [ChallengeController::class, 'getChallengeInvitations']);
    Route::get('/challenges/invitations/accept/{id}', [ChallengeController::class, 'acceptInvitation']);
    Route::get('/challenges/join/{id}', [ChallengeController::class, 'join']);
    Route::apiResource('/challenges', ChallengeController::class);

    //=============== plan ===============
    Route::get('/plans/{id}/schedule', [PlanController::class, 'getSchedule']);
    Route::apiResource('/plans', PlanController::class);

    //=============== workout ===============
    Route::post('/workout/result', [WorkoutController::class, 'storeResult']);

    //=============== profile ===============
    Route::get('/goals', [ProfileController::class, 'getGoals']);
    Route::put('/profile/{id}', [ProfileController::class, 'update']);
});
