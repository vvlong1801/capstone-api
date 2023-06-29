<?php

use App\Http\Controllers\WorkoutUser\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\WorkoutUser\ChallengeController;
use App\Models\Challenge;
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
    Route::get('/challenges/join/{id}', [ChallengeController::class, 'join']);
});
Route::apiResource('/challenges', ChallengeController::class);
