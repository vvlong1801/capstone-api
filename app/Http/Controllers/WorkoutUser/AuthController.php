<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Enums\Role;
use App\Http\Controllers\BaseAuthController;
use App\Http\Requests\WorkoutUser\Auth\LoginRequest;
use App\Http\Requests\WorkoutUser\Auth\RegisterRequest;
use App\Http\Resources\WorkoutUser\ProfileResource;
use App\Services\Interfaces\ProfileServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Auth\Events\Registered;


class AuthController extends BaseAuthController
{
    public function login(LoginRequest $request, ProfileServiceInterface $profileService)
    {
        $payload = $request->validated();

        try {
            $user = $this->authService->authenticate($payload, Role::workoutUser);
            $profile = $profileService->getProfileByUserId($user->id);
        } catch (\Throwable $th) {
            abort(500, $th->getMessage());
            // return $this->responseFailed($th->getMessage());
        }

        $response = [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'profile' => new ProfileResource($profile),
            'token_type' => 'Bearer',
        ];

        return $this->responseOk($response, "login success");
    }

    public function register(RegisterRequest $request, UserServiceInterface $userService)
    {
        $payload = $request->validated();

        try {
            // CREATE USER
            $user = $userService->createUserWithRole($payload, Role::workoutUser);
            // SEND EMAIL VERIFY
            event(new Registered($user));
        } catch (\Throwable $th) {
            abort(404, $th->getMessage());
        }

        return $this->responseNoContent('Registration successful');
    }
}
