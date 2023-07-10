<?php

namespace App\Services\Interfaces;

interface AccountServiceInterface
{
    public function getAccounts($payload = null);
    public function createAccount($payload);
    public function getAccountById($id);
    public function updateAccount($id, $payload);
    public function deleteAccount($id);
}
