<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateTizerViews extends Model
{
    public $timestamps = false;
    public $table = 'template_tizer_views';

    protected $fillable = [
        'tizer_id',
        'tempalte_id', 
    ];
}
