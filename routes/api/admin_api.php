<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\MuscleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Creator\ChallengeController;
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

Route::middleware('auth:sanctum')->group(function () {
    //=============== Auth ===============
    Route::post('/logout', [AuthController::class, 'logout']);

    //=============== Upload file ===============
    Route::post('/upload', [MediaController::class, 'upload']);
    Route::post('/media/create', [MediaController::class, 'create']);

    //=============== Muscles ===============
    Route::apiResource('/muscles', MuscleController::class)->except('show');

    //=============== Equipments ===============
    Route::apiResource('/equipments', EquipmentController::class)->except('show');

    //=============== Exercise ===============
    Route::get('/exercises/group_tags', [ExerciseController::class, 'getGroupTags']);
    Route::post('/exercises/search', [ExerciseController::class, 'search']);
    Route::apiResource('/exercises', ExerciseController::class);

    //=============== Challenge ===============
    Route::get('/challenges/tags', [ChallengeController::class, 'getChallengeTags']);
    Route::put('/challenges/{id}/information', [ChallengeController::class, 'updateBasicInformation']);
    Route::put('/challenges/{id}/confirm', [ChallengeController::class, 'confirmNewChallenge']);
    Route::apiResource('/challenges', ChallengeController::class);

    //=============== Tags ===============
    Route::apiResource('/tags', TagController::class);

    //=============== Users ===============
    Route::get('/users/search/{keyword}', [UserController::class, 'search']);
    Route::apiResource('/users', UserController::class);

    //=============== Dashboard ===============
    // Route::get('/dashboard/top-month/users', [DashboardController::class, 'newMembers']);
    // Route::get('/dashboard/top-month/challenges', [DashboardController::class, 'newChallenges']);
    // Route::get('/dashboard/top/challenges', [DashboardController::class, 'topChallenges']);
    // Route::get('/dashboard/users', [DashboardController::class, 'getMembers']);
    // Route::get('/dashboard/challenges', [DashboardController::class, 'getChallenges']);
    // Route::get('/dashboard/challenges/{id}/members', [DashboardController::class, 'getMembersOfChallenge']);
    // Route::get('/dashboard/overview', [DashboardController::class, 'getOverview']);
    // Route::get('/dashboard/top-creators', [DashboardController::class, 'topCreators']);
    Route::get('/analysis', [DashboardController::class, 'analysis']);
});
