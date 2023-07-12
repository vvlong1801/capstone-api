<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalTrainer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function creator(){
        return $this->belongsTo(Creator::class);
    }

    public function certificateIssuer(){
        return $this->belongsTo(CertificateIssuer::class);
    }

    public function certificateFile(){
        return $this->hasOne(Media::class);
    }
}
