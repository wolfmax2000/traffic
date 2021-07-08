<?php

namespace App\Http\Controllers;

use App\Template;
use App\Stat;
use App\TizerTest;
use App\Sids;
use App\Sites;
use App\Domain;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GeoIp2\Database\Reader;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Support\Facades\View;            
use Illuminate\Support\Facades\Route;

class FrontController extends Controller
{
    public $params = [];
    public $link_arr = [];
    public $isMobile = false;
    public $stat = null;
    public $city = "Москва";
    public $abtest = false;


    public $mix = false;
    public $header = false;
    public $domain = false;
    public $is_gray_mode = true;
    public $is_landing = false;

    public function __construct(Request $request)
    {
        

        $agent = new \Jenssegers\Agent\Agent; 
        $this->isMobile = $agent->isMobile();

        $this->params['country'] = "Россия";
        $country_en = "Russian Federation";
        if ( false && $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ) {
            $reader = new Reader(__DIR__ . '/../../../GeoLite2-City.mmdb');
            $record = $reader->city($_SERVER['REMOTE_ADDR']);

            if ( $record) { // это передаём для макроса [CITI]   
                $this->city = $record->city->names['ru'];                
            }

            // это передаём для алгоритма выдачи
            if ( $record && isset($this->isocountries[$record->country->isoCode]) ) {
                $this->params['country'] = $this->isocountries[$record->country->isoCode];
                $country_en = $record->country->names['en'];
            }
            
        }
        View::share('city', $this->city);
        View::share('country_en', $country_en);
        
        $this->link = $this->_collectLink($request);

        // domain                 
        $this->domain = Domain::where('title', $request->getHost())->first();       
    
        if ( $this->domain ) {            
            $this->is_landing = $this->domain->isLanding();
            $this->is_gray_mode = $this->domain->isGrayMode($this->params['country'], $this->isMobile, $request);
            if ( $this->is_landing && $this->is_gray_mode) {
                header('Location: ' . $this->domain->white_url);
            }

            View::share('domain', $this->domain);
            if ( !$this->is_landing ) {
                $this->header = $this->domain->getCRMParams();
                View::share('seo_title', $this->domain->seo_title);
                View::share('header', $this->header);
            } 
            
        }
        session_start();
        
        // template - для landing Template не исползуется
        $this->params['head_script'] = '';
        $this->params['body_script'] = '';

        if ( !$this->is_landing && $templateFound = Template::getTemplate($request, $this->domain) ) {            
            if ( $templateFound->mix ) {
                $this->mix = true;
            }
            $templateFound->load('categories');
            foreach ($templateFound->categories as $c) {
                if ( !isset($this->params['cats']) ) {
                    $this->params['cats'] = [];
                }
                $this->params['cats'][] = $c['id'];
            } 
            
            $this->params['head_script'] = $templateFound->head_script;
            $this->params['body_script'] = $templateFound->body_script;
            $this->params['template_id'] = $templateFound->id;
        }
 
        

        
    }

    protected function _collectLink(Request $request) {
        $params = [];
        foreach ($this->utms as $u) {
            if ( $request->has($u) ) {
                if ($u === 'sid8') {
             //       Cookie::queue('sid8', $request->get($u), 100);
                }
                $params[] = $u . '=' . $request->get($u);
            }
        }
        
        return implode('&', $params);
    }


}
