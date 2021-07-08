<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DomainView extends Model
{
    public $timestamps = false;
    public $table = 'domain_views';

    protected $fillable = [
        'domain_id',        
        'ip',  
    ];
}
