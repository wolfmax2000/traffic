<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateNewsViews extends Model
{
    public $timestamps = false;
    public $table = 'template_news_views';

    protected $fillable = [
        'news_id',
        'tempalte_id', 
    ];
}
