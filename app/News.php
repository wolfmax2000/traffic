<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

use Illuminate\Support\Facades\Cache;

use App\Traits\RowsTrait;
use Illuminate\Support\Facades\DB;

use Intervention\Image\Gd\Decoder;

class News extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, RowsTrait;

    public $table = 'news';

    protected $appends = [
        'image',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'desc',
        'title',
        'country',
        'cats',
        'views',
        'clicks',
        'short_desc',
        'created_at',
        'updated_at',
        'deleted_at',
        'color',
        'is_razgon',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(367)->height(268);
    }

    public function getImageAttribute()
    {
        $file = $this->getMedia('image')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }

    public function viewUp($template_id){
        $templTizerV = new TemplateNewsViews();
        $templTizerV->news_id = $this->id;
        $templTizerV->template_id = $template_id;
        $templTizerV->save();

        $ct = TemplateNews::where('template_id', $template_id)
            ->where('news_id', $this->id)->first();
        if ( !$ct ) {
            $ct = new TemplateNews();
            $ct->news_id = $this->id;
            $ct->template_id = $template_id;
            $ct->views = 0;
            $ct->clicks = 0;
            $ct->save();
        }

        $ct->increment('views');
    }

    public function clickUp($template_id){
        $templTizerC = new TemplateNewsClick();
        $templTizerC->news_id = $this->id;
        $templTizerC->template_id = $template_id;
        $templTizerC->save();


        $ct = TemplateNews::where('template_id', $template_id)
            ->where('news_id', $this->id)->first();
        if ( !$ct ) {
            $ct = new TemplateNews();
            $ct->news_id = $this->id;
            $ct->template_id = $template_id;
            $ct->views = 0;
            $ct->clicks = 0;
            $ct->save();
        }

        $ct->increment('clicks');
    }    

    static function algo($params = []) {

        $collect = self::select(DB::raw('`news`.*, IFNULL(`views`, 0) as `views`, IFNULL(`clicks`, 0) as `clicks`'))
            ->from('news') 
            ->leftJoin('template_news', 'news.id', '=', 'template_news.news_id')
            ->where(DB::raw('IFNULL(`template_id`, '.$params['template_id'].')'), $params['template_id'] );

        if ( !isset($params['razgon']) ) {
            if ( isset($params['random']) ) {
                $collect->orderBy(DB::raw('rand()')); 
            } else {
                $collect->orderBy(DB::raw('clicks/views'), 'DESC'); 
            }
            
        } else  {
            $collect->orderBy(DB::raw('clicks/views'), 'DESC');
            $template = Template::find($params['template_id']);
            $collect->where('is_razgon', true );
            $collect->where('views', "<", $template->news_boost_val ); 
        }      
        
        // обработка параметра excludeIds
        if ( isset($params['excludeIds']) ) {
            sort($params['excludeIds']);            
            $collect->whereNotIn('news.id', $params['excludeIds']);
        }

        if ( isset($params['country']) ) {
            $collect->where('country', $params['country']);
        }

        if ( isset($params['cats']) ) {
            $collect->where(function($q) use ($params) {
                foreach ($params['cats'] as $cat_id) {
                    $q->orWhereRaw('FIND_IN_SET(?, cats)', $cat_id);
                }                                  
            });  
        }
        
        return $collect;
    }


    public function getUrl($template_id=1, $link = '', $cont = '/news/') {
        $this->viewUp($template_id);
        
        if ( $link != '' ) {
            return $cont . $this->id . '?' . $link;
        }
        
        return $cont . $this->id;
    }

    public function getCtr() {
        return round($this->views > 0 ? $this->clicks / $this->views : null, 3) * 100;
    }

    public function getPrior() {
        return $this->getCtr();
    }

    public function getTitle() {
        return $this->title; 
    }

    public function getImage() {
        return  $this->image; 
    }

    public function getImageThumb() {
        $keyThumb = $_SERVER['HTTP_HOST'] . "-thumb-for-mews-id-" . $this->id;
        if ( true || !$url = Cache::get($keyThumb) ) {
            $url = $this->getImage() ?  $this->getImage()->getUrl('thumb') : false;              
            Cache::put($keyThumb, $url, 3600);
        }
        return $url;
    }

    public function getRGBText() {
        return !Is_null($this->color) ?  $this->color : '101, 86, 116'; 
    }

    public function getColor($image_path) {                
        $dec = new Decoder();
        $img = $dec->initFromPath($image_path)->getCore();
        $w = imagesx($img);
        $h = imagesy($img);
        $r = $g = $b = 0;

        for($y = 0; $y < $h; $y++) {
            for($x = 0; $x < $w; $x++) {
                $rgb = imagecolorat($img, $x, $y);
                $r += $rgb >> 16;
                $g += $rgb >> 8 & 255;
                $b += $rgb & 255;
            }
        }            
        
        $pxls = $w * $h;

        $r = round($r / $pxls);
        $g = round($g / $pxls);
        $b = round($b / $pxls);
        
        return $r . ', ' . $g . ', ' . $b;
    }
}
