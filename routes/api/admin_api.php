<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\MuscleController;
use App\Http\Controllers\Admin\UserController;
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
    Route::apiResource('/challenges', ChallengeController::class);

    //=============== Users ===============
    Route::get('/users/search/{keyword}', [UserController::class, 'search']);
    Route::apiResource('/users', UserController::class);
});
