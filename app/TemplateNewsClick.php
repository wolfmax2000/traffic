<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateNewsClick extends Model
{
    public $timestamps = false;
    public $table = 'template_news_clicks';

    protected $fillable = [
        'news_id',
        'tempalte_id', 
    ];
}
