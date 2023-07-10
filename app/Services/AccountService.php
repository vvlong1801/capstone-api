<?php

namespace App\Services;

use App\Enums\StatusAccount;
use App\Models\Account;
use App\Services\Interfaces\AccountServiceInterface;

class AccountService extends BaseService implements AccountServiceInterface
{
    public function getAccounts($payload = null)
    {
        $query = Account::query();

        if ($payload) {
            if ($role = \Arr::get($payload,'role', null)) $query->where('role', $role);
            if ($status = \Arr::get($payload, 'status', null)) $query->where('status', $status);
        }

        return $query->paginate(10);
    }

    public function createAccount($payload)
    {
        $account = Account::create([
            'role' => $payload['role'],
            'status' => StatusAccount::verified,
        ]);

        return $account;
    }

    public function getAccountById($id)
    {
        $account = Account::find($id);

        if (!$account) throw new \Exception("not found account");

        return $account;
    }

    public function updateAccount($id, $payload)
    {
        $account =  Account::find($id);
        if (!$account) throw new \Exception("not found account to update");
        
        $account->role = \Arr::get($payload, 'role', $account->role);
        $account->status = \Arr::get($payload, 'status', $account->status);

        return $account->update();
    }

    public function deleteAccount($id)
    {
        $account = Account::find($id)->delete();

        if (!$account) throw new \Exception("not found account to delete");
    }
}
