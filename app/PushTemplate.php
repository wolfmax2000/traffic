<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class PushTemplate extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    public $table = 'push_templates';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',        
        'url',
        'geo',
        'device',
        'top_type',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
