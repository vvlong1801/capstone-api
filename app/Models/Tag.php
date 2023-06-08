<?php

namespace App\Models;

use App\Enums\TypeTag;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => TypeTag::class,
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function scopeCreateOrIgnore(
        Builder $query,
        TypeTag $type,
        array $data
    ) {
        $tags = \Arr::map($data, function ($item) use ($type, $data) {
            $item['type'] = $type->value;
            $item['created_by'] = request()->user()->id;
            return $item;
        });

        $query->insertOrIgnore($tags);

        $result = $query->select('id')
            ->whereIn('name', \Arr::pluck($data, 'name'))->get()
            ->pluck('id')->toArray();

        return $result;
    }
}
