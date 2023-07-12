<?php

namespace App\Services;

use App\Enums\Role;
use App\Enums\StatusAccount;
use App\Models\Account;
use App\Models\User;
use App\Models\WorkoutUser;
use App\Services\Interfaces\UserServiceInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService implements UserServiceInterface
{
    public function getUsers()
    {
        $users = User::with(['account', 'avatar'])->get();
        return $users;
    }

    public function searchByEmailOrPhoneNumber(string $payload)
    {
        // approximate query on member of challenge that created by auth user
        //

        // exactly query on member of challenge that created by auth user
        $users = User::with(['account', 'avatar'])->whereEmail($payload)->orWhere('phone_number', $payload)->get();
        return $users;
    }

    public function createUserWithRole(array $data, Role $role)
    {

        \DB::beginTransaction();

        try {
            $account = Account::create([
                'role' => $role,
                'status' => StatusAccount::not_verified,
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'account_id' => $account->id,
                'first_login' => true,
            ]);

            if ($role == Role::workoutUser) {
                $user->workoutUser()->create();
            }

            \DB::commit();
            return $user;
        } catch (\Exception $e) {
            \DB::rollback();
            throw new Exception('your email was registered');
        }
    }
}
