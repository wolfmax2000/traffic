<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Source extends Model
{
    use SoftDeletes;


    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',        
        'utms',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scripts()
    {
        return $this->belongsToMany(Script::class);
    }

    static function getSource(Request $request) {
        if ( $request->has('sid8') ) {            
            return Source::find($request->get('sid8'));
        }
        
        return false;
    }
}
