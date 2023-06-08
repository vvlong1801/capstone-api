<?php

namespace App\Models;

use App\Enums\Level;
use App\Enums\PlanStatus;
use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
