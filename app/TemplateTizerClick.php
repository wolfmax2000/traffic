<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateTizerClick extends Model
{
    public $timestamps = false;
    public $table = 'template_tizer_clicks';

    protected $fillable = [
        'tizer_id',
        'tempalte_id', 
    ];
}
