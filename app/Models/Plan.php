<?php

namespace App\Models;

use App\Enums\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['challenge'];

    protected $casts = [
        'level' => Level::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class)->with(['mainImage', 'createdBy', 'tags'])
            ->withCount(['phases'])
            ->withSum('phases as total_sessions', 'total_days');
    }
}
