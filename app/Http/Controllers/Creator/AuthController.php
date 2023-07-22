<?php

namespace App\Http\Controllers\Creator;

use App\Enums\Role;
use App\Http\Controllers\BaseAuthController;
use App\Http\Requests\Creator\Auth\LoginRequest;
use App\Http\Requests\WorkoutUser\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Auth\Events\Registered;

class AuthController extends BaseAuthController
{
    public function login(LoginRequest $request)
    {
        $payload = $request->validated();

        try {
            $user = $this->authService->authenticate($payload, Role::creator);
            $response = [
                'access_token' => $user->createToken('auth_token')->plainTextToken,
                'token_type' => 'Bearer',
                'user_info' => new UserResource($user),
            ];
            if ($user->account->role == Role::creator) {
                $response = \Arr::add($response, 'creator_info', [
                    'is_PT' => $user->creator->isPT,
                    'rate' => $user->creator->rate,
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->responseOk($response, "login success");
    }

    public function register(RegisterRequest $request, UserServiceInterface $userService)
    {
        $payload = $request->validated();

        try {
            // CREATE USER
            $user = $userService->createUserWithRole($payload, Role::creator);
            // SEND EMAIL VERIFY
            event(new Registered($user));
        } catch (\Throwable $th) {
            return $this->responseFailed($th->getMessage());
        }

        return $this->responseNoContent('Registration successful');
    }
}
