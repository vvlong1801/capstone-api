<?php

namespace App\Models;

use App\Enums\StatusCertificateIssuer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateIssuer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'status' => StatusCertificateIssuer::class,
    ];

    public function exampleCertificate()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
