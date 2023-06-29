<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\BaseAuthController;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Resources\UserResource;

class AuthController extends BaseAuthController
{
    public function login(LoginRequest $request)
    {
        $payload = $request->validated();

        try {
            $user = $this->authService->authenticate($payload, Role::admin);
        } catch (\Throwable $th) {
            throw $th;
        }

        $response = [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
            'user_info' => new UserResource($user),
        ];

        return $this->responseOk($response, "login success");
    }
}
