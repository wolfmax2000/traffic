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

class TizerTest extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, RowsTrait;

    public $table = 'test';
    protected $_is_show_b = null;

    protected $appends = [
        'image',        
    ];

    protected $fillable = [
        'title',
        'tizer_id',
        'need_views',
        'views',
        'click_a',
        'click_b',
        'next_b',
        'created_at',
        'updated_at',
        'deleted_at',
        
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
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

    public function isShowB() {
        if ( is_null($this->_next) ) {
            $this->_is_show_b = $this->views_a > $this->views_b;
        }
        return $this->_is_show_b;
    }
    
    public function getNextState() {
        $this->tizer->test = $this;
        if ( $this->isShowB() ) {                                  
            $this->increment('views_b');
        } else {
            $this->increment('views_a');
        }

        return $this->tizer;
    }

    public function getImage() {
        return $this->image ? $this->image : $this->tizer->image;
    }

    public function getStatusColor() {
        if ( $this->views_a + $this->views_b >= $this->need_views ) {
            return $this->click_a >= $this->click_b ? "red" : "green";
        }

        return "white";
            
    }

    public function tizer()
    {
        return $this->belongsTo(Tizer::class);
    }

    public function getRGBText() {
        if ( $this->image ) {
            return !Is_null($this->color) ?  $this->color : '101, 86, 116'; 
        }

        return !Is_null($this->tizer->color) ?  $this->tizer->color : '101, 86, 116'; 
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
