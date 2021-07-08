<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PushClient extends Model 
{
    public $table = 'push_clients';
    public $timestamps = false;

    protected $fillable = [
        'client_id',
        'country',
        'city',
        'isMobil',
        'domen',
        'sid8'
    ];

}
