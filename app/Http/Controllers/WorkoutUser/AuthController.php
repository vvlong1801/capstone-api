<?php

namespace App\Http\Controllers\WorkoutUser;

use App\Enums\Role;
use App\Http\Controllers\BaseAuthController;
use App\Http\Requests\WorkoutUser\Auth\LoginRequest;
use App\Http\Requests\WorkoutUser\Auth\RegisterRequest;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Auth\Events\Registered;


class AuthController extends BaseAuthController
{
    public function login(LoginRequest $request)
    {
        $payload = $request->validated();

        try {
            $user = $this->authService->authenticate($payload, Role::workoutUser);
        } catch (\Throwable $th) {
            throw $th;
        }

        $response = [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
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
            abort(404, "can't create account");
        }

        return $this->responseNoContent('Registration successful');
    }
}
