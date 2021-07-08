<?php

namespace App\Http\Controllers;

use App\News;
use App\Tizer;
use App\TizerTest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Torann\GeoIP\GeoIP;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Intervention\Image\Gd\Decoder;


use App\Forecast;

class WeatherController extends FrontController
{            
    public function index() {

        $perPage = 28;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;        
        $all_news = News::algo($this->params)->skip(($page-1) * $perPage)->take($perPage)->get();

        $news = [];
        foreach($all_news as $item) {
            $pr = [];
            $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/news_click/');
            $pr['image'] = $item->getImageThumb();
            $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
            $pr['rgb'] = $item->getRGBText();
            $news[] = $pr;
        }

        $tizers = [];
        
        if ( isset($_GET['page']) ) {
            return response()->json([
                'news' => $news,
                'tizers' => $tizers,
            ]);
        }

        // ОПРЕДЕЛИЛИ В КАКОМ МЫ ГОРОДЕ
        $city = "Москва";
        
        $forecast = Forecast::getData($city);        

        $data = array_merge($forecast, [
            'currentDay' => date("Y-m-d"),
        ]);

        // одна новость                 
        $news1 = News::algo($this->params)->take(1)->get();

       // dd($news1[0]->image->thumbnail);
                                
        return view('weather.main', compact('data', 'news', 'tizers', 'news1', 'city'));

        /*return view('news.news50', [
            'news' => json_encode($news),
            'tizers' => json_encode([]),
            'link' => $this->link,
            'header' => $this->header, 
        ]);*/
    }  
    
    public function gis() {

        $perPage = 28;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;        
        $all_news = News::algo($this->params)->skip(($page-1) * $perPage)->take($perPage)->get();

        $news = [];
        foreach($all_news as $item) {
            $pr = [];
            $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/news_click/');
            $pr['image'] = $item->getImageThumb();
            $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
            $pr['rgb'] = $item->getRGBText();
            $news[] = $pr;
        }

        $tizers = [];
        
        if ( isset($_GET['page']) ) {
            return response()->json([
                'news' => $news,
                'tizers' => $tizers,
            ]);
        }

        // ОПРЕДЕЛИЛИ В КАКОМ МЫ ГОРОДЕ
        $city = "Ростов-на-Дону";
        
        $forecast = Forecast::getData($city);        

        $data = array_merge($forecast, [
            'currentDay' => date("Y-m-d"),
        ]);
                                
        return view('weather.gis', compact('data', 'news', 'tizers'));

        /*return view('news.news50', [
            'news' => json_encode($news),
            'tizers' => json_encode([]),
            'link' => $this->link,
            'header' => $this->header, 
        ]);*/
    }  
}
