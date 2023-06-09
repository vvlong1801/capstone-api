<?php

namespace App\Services;

use App\Enums\Role;
use App\Enums\StatusAccount;
use App\Models\Account;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService implements UserServiceInterface
{

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
            ]);
            \DB::commit();
            return $user;
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
