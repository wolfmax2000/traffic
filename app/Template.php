<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Template extends Model
{
    use SoftDeletes;

    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',        
        'stat_days',
        'utm_name',
        'utm_value',
        'head_script',
        'body_script',
        'tizer_boost_geo',
        'news_boost_geo',
        'tizer_boost_val',
        'news_boost_val',
        'mix',
        

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    static function getTemplate(Request $request, $domain = false) {
        if ( $request->has('template_id') ) {            
            return Template::find($request->get('template_id'));
        }

        if ( $domain ) {
            return Template::find($domain->template_id);
        }
         
        $templates = Template::whereRaw('utm_name is not null')->orderBy('utm_name', 'asc')->orderBy('utm_value', 'desc')->get();            
        foreach ($templates as $t) {                
            if (  $request->has($t['utm_name']) && ( !$t['utm_value'] || $t['utm_value'] == $request->get($t['utm_name']) ) ) {
                return $t;                
            }                             
        }        

        return Template::find(1);
    }
}
