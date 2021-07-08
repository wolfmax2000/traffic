<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\DB;


class Domain extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'domains';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'image',
        'banner',
    ];

    protected $fillable = [
        'title',
        'template_id',      
        'created_at',
        'updated_at',
        'deleted_at',        
        'site_name',
        'site_news_device',
        'site_news_no_geo',
        'lang',
        'user_accept',
        'contacts',
        'color1',
        'color2',
        'color3',
        'color4',
        'menu_items',
        'seo_title',
        'last_hours',
        'need_views',
        'coockie_text',
        'coockie_page_text',
        'coockie_button',
        'banner_url',
        'info_txt_3',
        'info_txt_2',
        'info_txt_1',
        'head_script',
        'body_script',
        'type',


        'black_html',
        'black_css',
        'black_js',

        'white_html',
        'white_css',
        'white_js',
        
        'white_url',
    ];

    static $types = [
        'simple' => 'Простая витрина',
        'kloaka' => 'Витрина с клоакой',
        'landing' => 'landing с клоакой',        
    ];

    public function isKloaka() {
        return $this->type == 'kloaka' || $this->type == 'landing';
    }

    public function isLanding() {
        return $this->type === 'landing';
    }

    // Если True , то показывать тизеры, если false - то для модератора показывать только новости
    public function isGrayMode($currentGeo, $isMobile, $request )
    {      
        // Если не стоит галочка "Новостная витрина", то показываем Серую витрину, и не проверяем никакие условия
        if (!$this->isKloaka()) 
        {    
            return true; // return - значит вернут результат немедленно , в данном случае "ДА" - это серая витрина
        }

        // ** Должны выполнится 3 условия чтобы показать GRAY

        $grayMode = false; // по умолчанию будем думать что НЕ ПРОШЛА

        if ( $this->site_news_no_geo == 'all_country' ) {
            return false;
        }

        // если страна выбрана  в настройках и она не совпадает со страной пользоватедя
        if ( $this->site_news_no_geo === $currentGeo ) {
            $grayMode = true;
        }

        // если страна выбрана  в настройках и она не совпадает со страной пользоватедя
        if ($grayMode && $this->lang && isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
            $grayMode = false;
            $curLang = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));            
            if ( $curLang === strtolower($this->lang) ) {
                return true;
            }
        }
        
        
        // Если проверка на страну прошла, то проверить на девайс, но проверять если не стоит ALL
        if ( $grayMode && $this->site_news_device !== 'all' ) 
        { 
            $grayMode = false;
            // Если стоит mobil и зашли не с мобилки, то ставим FALSE - и следующую проверку на МЕТКИ НЕ ДЕЛАЕМ
            if (  $this->site_news_device == 'mobile' && !$isMobile )
            { 
                $grayMode = true;
            }

            // Если же стоит desktop и зашли не с Кома, то ставим FALSE - и следующую проверку на МЕТКИ НЕ ДЕЛАЕМ
            if (  $this->site_news_device == 'desktop' && $isMobile )
            {
                $grayMode = true;
            }
        }
        
        // Если проверка на устройства прошла и есть в запросе метки sid8 и utm_source, то вернем результат TRUE - показать серую витрину
        if ($grayMode && $request->has('sid8') && $request->has('utm_source') )
        {   
            $grayMode = true;
        }

        // тут запишем и проверим временную клоаку
        if ($grayMode) {            
            return !$this->timeCloakaBlock();
        }
        
        return false; // иначе вернум НЕ ПРОШЛА                
    }

    public function getCRMParams()
    {
        if (!$this->menu_items) {
            $this->menu_items = "Картина дня||Общество||События";
        }
        $menu = [];
        $links = ['day_news', 'social_news', 'actions'];
        $menu_items = explode('||', $this->menu_items);
        foreach ($menu_items  as $key => $item) {if ($key>2) $key =2;
            $menu[] = [                    
                'link' => '/' . $links[$key],
                'title' => $item
            ];
        }

        return [
            'logo' => $this->site_name,
            'contacts' => $this->contacts,
            'color1' => $this->color1,
            'color2' => $this->color2,
            'color3' => $this->color3,
            'color4' => $this->color4,
            'menu' => $menu,
            'seo_title' => $this->seo_title,
            'logo_img' => $this->getImage() ? $this->getImage()->getUrl('thumb') : false,
            'banner' => $this->getBanner() ? $this->getBanner()->getUrl('thumb') : false,
            'banner_url' => $this->banner_url,
            'info_img_1' => $this->getInfo1() ? $this->getInfo1()->getUrl('info') : false,
            'info_img_2' => $this->getInfo2() ? $this->getInfo2()->getUrl('info') : false,
            'info_img_3' => $this->getInfo3() ? $this->getInfo3()->getUrl('info') : false,
            'info_txt_1' => $this->info_txt_1,
            'info_txt_2' => $this->info_txt_2,
            'info_txt_3' => $this->info_txt_3,
            'show_coockie' => $this->coockie_text && strlen(trim($this->coockie_text)),
            'coockie_text' => $this->coockie_text,
            'coockie_button' => $this->coockie_button,
            'head_script' => $this->head_script,
            'body_script' => $this->body_script,
        ];
    }

    // Набралось ли достаточное колличество уников за последний час
    public function timeCloakaBlock() 
    {
        if ( $this->need_views < 1 ) {
            return false;
        }

        $hours = $this->last_hours;
        $needViews = $this->need_views;
        $this->viewsUniqueUp(); 

        $viewsFact = DB::table('domain_views')
            ->distinct('ip')
            ->where('domain_id', $this->id)
            ->where('time', '>', DB::raw("DATE_SUB(NOW(), INTERVAL $hours HOUR)"))      
            ->count('ip');      

        return $viewsFact <= $needViews;
    }

    // зачтем посещеение если это уник (например реферер не включает имя домена)
    public function viewsUniqueUp() {
        $write = !isset($_GET['page']);        

        if ($write) {
            $templTizerC = new DomainView();
            $templTizerC->domain_id = $this->id;        
            $templTizerC->ip = $_SERVER["REMOTE_ADDR"];
            $templTizerC->save();
        }
        
    }

    public function registerMediaConversions(Media $media = null)
    {
            $this->addMediaConversion('thumb')->width(100)->height(100);
            $this->addMediaConversion('banner')->width(268)->height(450);
            $this->addMediaConversion('info')->width(100)->height(50);
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

    public function getImage() {
        return $this->image; 
    }

    public function getBannerAttribute()
    {
        $file = $this->getMedia('banner')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('banner');
        }

        return $file;
    }

    public function getBanner() {
        return $this->banner; 
    }

    public function getInfo1() {
        return $this->info1; 
    }

    public function getInfo2() {
        return $this->info2; 
    }
    public function getInfo3() {
        return $this->info3; 
    }

    public function getInfo1Attribute()
    {
        $file = $this->getMedia('info1')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('info');
        }

        return $file;
    }

    public function getInfo2Attribute()
    {
        $file = $this->getMedia('info2')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('info');
        }

        return $file;
    }

    public function getInfo3Attribute()
    {
        $file = $this->getMedia('info3')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('info');
        }

        return $file;
    }

    
}
