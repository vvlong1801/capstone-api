<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Services\Interfaces\AccountServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreAccountRequest;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    protected $accountService;
    public function __construct(AccountServiceInterface $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = $this->accountService->getAccounts();
        return $this->responseOk(AccountResource::collection($accounts));
    }

    /**
     * Display a listing of search.
     */
    public function search(Request $request)
    {
        $accounts = $this->accountService->getAccounts($request);
        return $this->responseOk(AccountResource::collection($accounts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        $payload = $request->validated();

        try {
            $this->accountService->createAccount($payload);
            return $this->responseNoContent('Account was created');
        } catch (\Throwable $th) {
            abort(404, $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $account = $this->accountService->getAccountById($id);
            $account = new AccountResource($account);

            return $this->responseOk($account, 'get accounts success');
        } catch (\Throwable $th) {
            abort(404, 'not found data');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAccountRequest $request, int $id)
    {
        $payload = $request->validated();

        try {
            $this->accountService->updateAccount($id, $payload);
            return $this->responseNoContent('Account was updated');
        } catch (\Throwable $th) {
            abort(404, $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->accountService->deleteAccount($id);
            return $this->responseNoContent('Account was deleted');
        } catch (\Throwable $th) {
            abort(Response::HTTP_BAD_REQUEST, $th->getMessage());
        }
    }
}
