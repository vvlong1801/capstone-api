<?php

use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\MuscleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Creator\AuthController;
use App\Http\Controllers\Creator\ChallengeController;
use App\Http\Controllers\Creator\ProfileController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//=============== Auth ===============
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    //=============== Auth ===============
    Route::post('/logout', [AuthController::class, 'logout']);

    //=============== Upload file ===============
    Route::post('/upload', [MediaController::class, 'upload']);

    //=============== Muscles ===============
    Route::apiResource('/muscles', MuscleController::class)->only('index');

    //=============== Equipments ===============
    Route::apiResource('/equipments', EquipmentController::class)->only('index');

    //=============== Exercise ===============
    Route::get('/exercises/group_tags', [ExerciseController::class, 'getGroupTags']);
    Route::post('/exercises/search', [ExerciseController::class, 'search']);
    Route::apiResource('/exercises', ExerciseController::class);

    //=============== Challenge ===============
    Route::get('/challenges/tags', [ChallengeController::class, 'getChallengeTags']);
    Route::get('/challenges/{id}/feedback', [ChallengeController::class, 'getFeedbacks']);
    Route::get('/challenges/{id}/comment', [ChallengeController::class, 'getComments']);
    Route::put('/challenges/{challengeId}/feedback/{feedbackId}/reply', [ChallengeController::class, 'replyFeedback']);
    Route::post('/challenges/request-join/confirm', [ChallengeController::class, 'confirmNewChallengeMember']);
    Route::put('/challenges/{id}/information', [ChallengeController::class, 'updateBasicInformation']);
    Route::put('/challenges/{id}/invitation', [ChallengeController::class, 'updateInvitation']);
    Route::apiResource('/challenges', ChallengeController::class);

    //=============== Users ===============
    Route::get('/users/search/{keyword}', [UserController::class, 'search']);

    //=============== Profile ===============
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/request-became-pt', [ProfileController::class, 'requestBecomePersonalTrainer']);

    //=============== Others ===============
    Route::get('/certificate-issuers', [ProfileController::class, 'getCertificateIssuers']);
    Route::get('/techniques', [ProfileController::class, 'getTechniques']);
});
