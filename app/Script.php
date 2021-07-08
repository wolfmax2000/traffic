<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Script extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'code',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function ScriptsSources()
    {
        return $this->belongsToMany(Source::class);
    }
}
