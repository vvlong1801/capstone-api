<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateIssuer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function exampleCertificate()
    {
        return $this->hasOne(Media::class);
    }
}
