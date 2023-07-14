<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use App\Services\Interfaces\CreatorServiceInterface;
use Exception;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CreatorService extends BaseService implements CreatorServiceInterface
{

    public function authenticate(array $data, Role $role)
    {
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ["The account hasn't verified email!"],
            ]);
        }

        if (!$user->hasCreatorPermissions && (\Str::contains(request()->route()->uri, 'creator') || \Str::contains(request()->route()->uri, 'admin'))) {
            throw ValidationException::withMessages([
                'email' => ["The account hasn't permission !"],
            ]);
        }

        return $user;
    }

    public function verifyEmail($id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            throw new ValidationException("Email already verified");

            // return $this->responseNoContent("Email already verified");
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new ValidationException("Invalid verification link");
            // return $this->responseNotFound("Invalid verification link");
        }

        if (!$user->markEmailAsVerified()) {
            throw new Exception("Email verification failed");
            // return $this->responseFailed("Email verification failed");
        } else {
            event(new Verified($user));
        }
    }
}
