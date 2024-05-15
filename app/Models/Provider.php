<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Provider extends Model
{
    use HasFactory;

    public static function getAllSearchableFields(): Collection
    {
        return collect(self::pluck('search_fields')->map(function ($searchFields) {
            return json_decode($searchFields);
        }))->flatten()->unique();
    }
}
