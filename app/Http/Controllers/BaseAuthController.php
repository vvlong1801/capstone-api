<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;

class BaseAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        try {
            $this->authService->verifyEmail($id, $hash);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->responseNoContent("Email verified successfully");
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->responseNoContent("logout success");
    }
}
