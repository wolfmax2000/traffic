<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

use App\Traits\RowsTrait;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Gd\Decoder;

use Illuminate\Support\Facades\Cache;

class Tizer extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, RowsTrait;

    public $table = 'tizers';

    public $test = false;    

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
        'ext_link',
        'views',
        'price',
        'clicks',
        'aprove',
        'created_at',
        'updated_at',
        'deleted_at',
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
        $templTizerV = new TemplateTizerViews();
        $templTizerV->tizer_id = $this->id;
        $templTizerV->template_id = $template_id;
        $templTizerV->save();

        $ct = TemplateTizer::where('template_id', $template_id)
            ->where('tizer_id', $this->id)->first();
        if ( !$ct ) {
            $ct = new TemplateTizer();
            $ct->tizer_id = $this->id;
            $ct->template_id = $template_id;
            $ct->views = 0;
            $ct->clicks = 0;
            $ct->save();        }

        $ct->increment('views');
    }

    public function clickUp($template_id){
        $templTizerC = new TemplateTizerClick();
        $templTizerC->tizer_id = $this->id;
        $templTizerC->template_id = $template_id;
        $templTizerC->save();

        $ct = TemplateTizer::where('template_id', $template_id)
            ->where('tizer_id', $this->id)->first();
        if ( !$ct ) {
            $ct = new TemplateTizer();
            $ct->tizer_id = $this->id;
            $ct->template_id = $template_id;
            $ct->views = 0;
            $ct->clicks = 0;
            $ct->save();
        }

        $ct->increment('clicks');
    }

    public function getUrl($template_id = 1, $link = '') {      
        $this->viewUp($template_id);

        if ( $this->test ) {
            if ( $link != '' ) {
                return '/tizers/test/' . $this->test->id . '/' . ($this->test->isShowB() ? 'b' : 'a') . '?' . $link;
            }
            
            return '/tizers/test/' . $this->test->id . '/' . ($this->test->isShowB() ? 'b' : 'a');
        } else {
            if ( $link != '' ) {
                return '/tizers/' . $this->id . '?' . $link;
            }
            
            return '/tizers/' . $this->id;
        }        
    }

    static function algo($params = []) 
    {
        $collect = self::select(DB::raw('`tizers`.*, IFNULL(`views`, 0) as `views`, IFNULL(`clicks`, 0) as `clicks`'))
            ->from('tizers') 
            ->leftJoin('template_tizer', 'tizers.id', '=', 'template_tizer.tizer_id')
            ->where(DB::raw('IFNULL(`template_id`, '.$params['template_id'].')'), $params['template_id'] );
        
        if ( isset($params['tizer_test_id']) ) {
            $collect->where('tizers.id', $params['tizer_test_id'] );
        } elseif ( isset($params['razgon']) ) {
            $collect->orderBy(DB::raw('views'), 'ASC');
            $template = Template::find($params['template_id']);
            $collect->where('is_razgon', true );
            $collect->where('views', "<", $template->tizer_boost_val ); 
        } else {
            $collect->orderBy(DB::raw('aprove * price * clicks/views'), 'DESC');
        }
        
        // обработка параметра excludeIds - для того чтобы в выборку не попадали тизеры, которые в разгоне, например
        if ( isset($params['excludeIds']) ) {
            sort($params['excludeIds']);            
            $collect->whereNotIn('tizers.id', $params['excludeIds']);
        }

        $collect->where('is_active', true);
        if ( isset($params['country']) ) {
            $collect->where('country', $params['country']);
        }

        if ( isset($params['cats']) ) {
            sort($params['cats']);
            $collect->where(function($q) use ($params) {
                foreach ($params['cats'] as $cat_id) {
                    $q->orWhereRaw('FIND_IN_SET(?, cats)', $cat_id);
                }                                  
            });  
        }

        return $collect;
    }

    public function getCtr() {
        return round($this->views > 0 ? $this->clicks / $this->views : null, 3) * 100;
    }

    public function getPrior() {
        return $this->aprove/100 * $this->price * $this->getCtr();
    }

    public function getTitle() {
        if ( $this->test && $this->test->title && $this->test->isShowB() ) {
            return $this->test->title;
        }

        return $this->title; 
    }

    public function getImage() {
        if ( $this->test && $this->test->image && $this->test->isShowB() ) {
            return $this->test->image;
        }

        return $this->image; 
    }

    public function getImageThumb() {
        $keyThumb = $_SERVER['HTTP_HOST'] . "-thumb-for-tizer-id-" . $this->id;
        if ( !$url = Cache::get($keyThumb) ) {
            $url = $this->getImage() ? $this->getImage()->getUrl('thumb') : false;   

            Cache::put($keyThumb, $url, 100);
        }

        return $url;
    }

    public function getRGBText() {
        if ( $this->test && $this->test->image && $this->test->isShowB() ) {
            return !Is_null($this->test->color) ?  $this->test->color : '101, 86, 116';
        }

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
