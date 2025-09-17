<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "name",
        "identifier",
        "url",
        "api_key",
        "is_active"
    ];

    public static function getActiveSources($fields){
        return self::where('is_active', 1)->get($fields);
    }
}
