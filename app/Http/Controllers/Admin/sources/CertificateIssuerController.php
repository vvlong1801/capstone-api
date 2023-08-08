<?php

namespace App\Http\Controllers\Admin\sources;

use App\Enums\MediaCollection;
use App\Enums\StatusCertificateIssuer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCertificateIssuerRequest;
use App\Http\Resources\CertificateIssuerResource;
use App\Models\CertificateIssuer;
use App\Services\Interfaces\MediaServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateIssuerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificates = CertificateIssuer::with("exampleCertificate")->get();
        return $this->responseOk(CertificateIssuerResource::collection($certificates), 'get success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificateIssuerRequest $request, MediaServiceInterface $mediaService)
    {
        $payload = $request->validated();
        try {
            DB::beginTransaction();
            $exampleCertificate = $mediaService->createMedia($payload['example'], MediaCollection::ExampleCertificate);
            $newIssuer = CertificateIssuer::create(\Arr::only($payload, ['name', 'description']));
            $newIssuer->exampleCertificate()->save($exampleCertificate);
            DB::commit();
            return $this->responseNoContent("create certificate issuer success");
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, "can't create certificate");
        }
    }

    /**
     * Display the specified resource.
     */
    public function lock(string $id)
    {
        CertificateIssuer::whereId($id)->update(['status' => StatusCertificateIssuer::locked]);
        return $this->responseNoContent('certificate was locked');
    }

    /**
     * Display the specified resource.
     */
    public function unlock(string $id)
    {
        CertificateIssuer::whereId($id)->update(['status' => StatusCertificateIssuer::active]);
        return $this->responseNoContent('certificate was unlocked');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CertificateIssuer::destroy($id);
        return $this->responseNoContent('certificate has been deleted');
    }
}
