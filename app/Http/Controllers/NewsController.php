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

use App\Forecast;


use Intervention\Image\Gd\Decoder;

class NewsController extends FrontController
{

    public function landing() {
        $indexHtml = __DIR__ . '/../../../landings/'.$this->domain->title. '/index.html';
        if (!is_file($indexHtml)) {
            header("HTTP/1.0 404 index.html Not Found");

            exit;    
        }
        $index = file_get_contents(__DIR__ . '/../../../landings/'.$this->domain->title. '/index.html');                
        echo $index;
        exit;        
    }

    public function showimage($img) {
        echo $img;
        die();
    }

    /**
     * Обычный список новостей
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( $this->is_landing ) {
            return $this->landing();
        }

        if ( $this->header ) {
            return $this->news_index();
        }

        $firstSkip = 0;
        $nearRazgon = false;

        $keyCacheRazgon = "razgon-" . "country=".$this->params['country']."template_id=".$this->params['template_id']."cats=".implode(",", $this->params['cats']);
        if ( !$razgon = Cache::get($keyCacheRazgon) ) {
            $razgon = News::algo(array_merge($this->params, ['razgon' => true]))->first();   
            Cache::put($keyCacheRazgon, $razgon, 60);
        }
        
        if ( $razgon ) {
            $this->params['excludeIds'] = array();
            $this->params['excludeIds'][] = $razgon['id'];
        }

        if ( $razgon ) {
            $firstSkip = 1;
            $nearRazgon = News::algo($this->params)->take($firstSkip)->get();
        } 
    
        $skip = isset( $_GET['skip'] ) ? intval($_GET['skip']) : $firstSkip;
        $page = isset( $_GET['page'] ) ? intval($_GET['page']) : 0;

        $rows = News::getRows($skip, $page, $this->params);

        // ПОГОДА
        // ОПРЕДЕЛИЛИ В КАКОМ МЫ ГОРОДЕ
        $city = "Ростов-на-Дону";
        $forecast = Forecast::getData($city);        
        $weather = array_merge($forecast, [
            'currentDay' => date("Y-m-d"),
        ]);
    
        return view('tizer.index', [
            'nearRazgon' => $nearRazgon,
            'razgon' => $razgon,
            'isMobile' => $this->isMobile,
            'rows' => $rows,            
            'nextUrl' => '/?page=' .  $page . '&skip=' . $skip . '&' . $this->link,            
            'link' => $this->link,
            'params' => $this->params,
            'show_as' => '/news_click/',
            'weather' => $weather,
        ]);
    }

    /* news50 - список новостей на VUEJS */
    public function news_index() {        
        if ( $this->is_landing ) {
            return $this->landing();
        }


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
        
        if ( isset($_GET['page']) ) {
            return response()->json([
                'news' => $news,
                'tizers' => [],
            ]);
        }

        return view('news.news50', [
            'news' => json_encode($news),
            'tizers' => json_encode([]),
            'link' => $this->link,
            'header' => $this->header, 
        ]);
    }

    public function show_50(int $id)
    {
        if ( $this->is_landing ) {
            return $this->landing();
        }

        $perPage = 14;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $one_news = News::find($id);

        if ( !$one_news ) {
            abort(404);
        }

        $newsObj = [];
        $pr = [];

        $pr['href'] = $one_news->getUrl($this->params['template_id'], $this->link, '/news_click_full/');
        $pr['image'] = $one_news->getImageThumb();
        $pr['title'] = str_replace("[CITY]", $this->city, $one_news->getTitle());
        $pr['desc'] = $one_news->desc;
        $pr['rgb'] = $one_news->getRGBText();
        $pr['id']  = $id;
        $newsObj[] = $pr;  


        $news = [];
        $newsParams = $this->params;
        $newsParams['excludeIds'] = array();
        $newsParams['excludeIds'][] = $one_news['id'];  // Не будем показывать в списке эту новость
        $all_news = News::algo($newsParams)->skip(($page-1) * $perPage)->take($perPage)->get();
        foreach($all_news as $item) {
            $pr = [];
            $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/news_click_full/');
            $pr['image'] = $item->getImageThumb();
            $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
            $pr['rgb'] = $item->getRGBText();
            $news[] = $pr;
        }

        
        $tizers = [];
        if ( $this->is_gray_mode )  {
            $all_tizers = Tizer::algo($this->params)->skip(($page-1) * $perPage)->take($perPage)->get();
            foreach($all_tizers as $item) {
                $pr = [];
                $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/tizer_click/');
                $pr['image'] = $item->getImageThumb();
                $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
                $pr['rgb'] = $item->getRGBText();
                $tizers[] = $pr;
            }
        }

        if ( isset($_GET['page']) ) {
            return response()->json([
                'news' => $news,
                'tizers' => $tizers,
            ]);
        }

        if ( $this->is_gray_mode )  {
            $right_item_list = Tizer::algo($this->params)->take(1)->get();
            $right_item = [];
            foreach($right_item_list as $item) {
                $pr = [];
                $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/tizer_click/');
                $pr['image'] = $item->getImageThumb();
                $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
                $pr['rgb'] = $item->getRGBText();
                $right_item[] = $pr;
            }    
        } else {
            $right_item_list = News::algo($this->params)->take(1)->get();
            $right_item = [];
            foreach($right_item_list as $item) {
                $pr = [];
                $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/news_click/');
                $pr['image'] = $item->getImageThumb();
                $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
                $pr['rgb'] = $item->getRGBText();
                $right_item[] = $pr;
            }    
        }

        return view('news.show_50', [  
            'id' => $id,          
            'item' => json_encode($newsObj[0]),
            'right_item' => json_encode($right_item),
            'news' => json_encode($news),
            'tizers' => json_encode($tizers),
            'link' => $this->link,   
            'isMobile' => $this->isMobile,   
            'params' => $this->params,
            'show_as' => '/news_click_full/'    ,
            'header' => $this->header,    
        ]);
    }

    public function show_short_50(int $id)
    {

        if ( $this->is_landing ) {
            return $this->landing();
        }

        $perPage = 14;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $one_news = News::find($id);

        if ( !$one_news ) {
            abort(404);
        }

        $newsObj = [];
        $pr = [];

   
        $pr['href'] = $one_news->getUrl($this->params['template_id'], $this->link, '/news_click_full/');
        $pr['image'] = $one_news->getImageThumb();
        $pr['title'] = str_replace("[CITY]", $this->city, $one_news->getTitle());
        $pr['rgb'] = $one_news->getRGBText();
        $pr['id']  = $id;
        $newsObj[] = $pr;  


        $news = [];
        $newsParams = $this->params;
        $newsParams['excludeIds'] = array();
        $newsParams['excludeIds'][] = $one_news['id'];  // Не будем показывать в списке эту новость
        $all_news = News::algo($newsParams)->skip(($page-1) * $perPage)->take($perPage)->get();
        foreach($all_news as $item) {
            $pr = [];
            $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/news_click_full/');
            $pr['image'] = $item->getImageThumb();
            $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
            $pr['rgb'] = $item->getRGBText();
            $news[] = $pr;
        }

        $tizers = [];
        if ( $this->is_gray_mode )  {
            
            $all_tizers = Tizer::algo($this->params)->skip(($page-1) * $perPage)->take($perPage)->get();
            
            foreach($all_tizers as $item) {
                $pr = [];
                $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/tizer_click/');
                $pr['image'] = $item->getImageThumb();
                $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
                $pr['rgb'] = $item->getRGBText();
                $tizers[] = $pr;
            }
        }

        if ( isset($_GET['page']) ) {
            return response()->json([
                'news' => $news,
                'tizers' => $tizers,
            ]);
        }

        if ( !$this->is_gray_mode )  {
            $right_item_list = News::algo($this->params)->take(1)->get();
          
            $right_item = [];
            foreach($right_item_list as $item) {
                $pr = [];
                $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/news_click/');
                $pr['image'] = $item->getImageThumb();
                $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
                $pr['rgb'] = $item->getRGBText();
                $right_item[] = $pr;
            } 
        } else {
            $right_item_list = Tizer::algo($this->params)->take(1)->get();
            if ( !is_null($this->stat) ) {
                $this->stat->increment('news_views');
            }
            $right_item = [];
            foreach($right_item_list as $item) {
                $pr = [];
                $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/tizer_click/');
                $pr['image'] = $item->getImageThumb();
                $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
                $pr['rgb'] = $item->getRGBText();
                $right_item[] = $pr;
            } 
        }
           
        return view('news.show_short_50', [  
            'id' => $id,          
            'item' => json_encode($newsObj[0]),
            'right_item' => json_encode($right_item),
            'news' => json_encode($news),
            'tizers' => json_encode($tizers),
            'link' => $this->link,   
            'isMobile' => $this->isMobile,   
            'params' => $this->params,
            'show_as' => '/news_click_full/'  ,
            'header' => $this->header,    
        ]);
    }


    /* news50 - смешанные новости и тизеры */
    public function news() { 
        
        if ( $this->is_landing ) {
            return $this->landing();
        }

        $perPage = 14;
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
        if ( $this->is_gray_mode )  {
            $all_tizers = Tizer::algo($this->params)->skip(($page-1) * $perPage)->take($perPage)->get();               
            foreach($all_tizers as $item) {
                $pr = [];
                $pr['href'] = $item->getUrl($this->params['template_id'], $this->link, '/tizer_click/');
                $pr['image'] = $item->getImageThumb();
                $pr['title'] = str_replace("[CITY]", $this->city, $item->getTitle());
                $pr['rgb'] = $item->getRGBText();
                $tizers[] = $pr;
            }
        }

        if ( isset($_GET['page']) ) {
            return response()->json([
                'news' => $news,
                'tizers' => $tizers,
            ]);
        }

        return view('news.news50', [
            'news' => json_encode($news),
            'tizers' => json_encode($tizers),
            'link' => $this->link,
        ]);
    }
    
    


    public function click(int $id)
    {
        $news = News::find($id);
        if ( !$news ) {
            abort(404);
        }

        $news->clickUp($this->params['template_id']);

        return Redirect::to('/news_short/' . $id . '?' . $this->link);
    }

    public function click_full(int $id)
    {
        $news = News::find($id);
        if ( !$news ) {
            abort(404);
        }

        $news->clickUp($this->params['template_id']);

        return Redirect::to('/news/' . $id . '?' . $this->link);
    }

   
    /**
     * Display the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if ( $this->is_landing ) {
            return $this->landing();
        }

        if ( $this->mix || !$this->is_gray_mode ) {
            return $this->show_50($id);
        }

        $news = News::find($id);
        if ( !$news ) {
            abort(404);
        }

        if ( !is_null($this->stat) ) {
            $this->stat->increment('news_views');
        }

        $foundTestTizer = false;        
        if ( $this->abtest ) {
            $foundTestTizer = Tizer::algo(array_merge($this->params, ['tizer_test_id' => $this->abtest->tizer_id]))
                ->first();                
        }                    
        
        if ( !$foundTestTizer ) {
            $keyCacheRazgon = "razgon-" . "country=".$this->params['country']."template_id=".$this->params['template_id']."cats=".implode(",", $this->params['cats']);
            if ( !$razgon = Cache::get($keyCacheRazgon) ) {
                $razgon = Tizer::algo(array_merge($this->params, ['razgon' => true]))->first();   
                Cache::put($keyCacheRazgon, $razgon, 60);
            }
        } else {            
            $razgon = $this->abtest->getNextState();
        }
        
        if ($razgon) {
            $this->params['excludeIds'] = array();
            $this->params['excludeIds'][] = $razgon['id'];
        }

        $rightCount = $razgon ? 2 : 3;

        $tizers3 = Tizer::algo($this->params)->take($rightCount)->get();
             
        $skip = isset( $_GET['skip'] ) ? intval($_GET['skip']) : $rightCount;
        $page = isset( $_GET['page'] ) ? intval($_GET['page']) : 0;
        $rows = Tizer::getRows($skip, $page, $this->params);
        
        return view('news.show', [ 
            'razgon' => $razgon,
            'show_as' => false,
            'item' => $news,
            'tizers3' => $tizers3,
            'isMobile' => $this->isMobile,
            'rows' => $rows,            
            'nextUrl' => '/news/' . $news->id . '?page=' . $page . '&skip=' . $skip . "&" .  $this->link,
            'link' => $this->link,            
            'params' => $this->params,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show_short(int $id)
    {
        if ( $this->is_landing ) {
            return $this->landing();
        }

        if ( $this->mix || !$this->is_gray_mode ) {
            return $this->show_short_50($id);
        }

        $news = News::find($id);
        if ( !$news ) {
            abort(404);
        }

        if ( !is_null($this->stat) ) {
            $this->stat->increment('news_views');
        }

        $tizers2 = [];
        if ( count($tizers2) < 1 )      
            $tizers2 = Tizer::algo($this->params)->take(1)->get();
         
        $skip = isset( $_GET['skip'] ) ? intval($_GET['skip']) : 1;
        $page = isset( $_GET['page'] ) ? intval($_GET['page']) : 0;

        $rows = Tizer::getRows($skip, $page, $this->params);

        return view('news.show_short', [
            'link' => $this->link,
            'short' => true,
            'item' => $news,
            'tizers2' => $tizers2,
            'isMobile' => $this->isMobile,
            'rows' => $rows,            
            'nextUrl' => '/news_short/' . $news->id . '?page=' .  $page  . '&skip=' . $skip . '&' . $this->link ,
            'params' => $this->params,
            'show_as' => '/news_click_full/'
        ]);
    }



    public function users_guide() {
        if ( $this->is_landing ) {
            return $this->landing();
        }


        return view('news.users_guide', [
            'domain' => $this->domain
        ]);
    }

    public function users_coockie() {
        if ( $this->is_landing ) {
            return $this->landing();
        }

        return view('news.users_coockie', [
            'domain' => $this->domain
        ]);
    }

    /* news50 - новости в случайном порядке */
    public function random() {        

        if ( $this->is_landing ) {
            return $this->landing();
        }

        $perPage = 28;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1; 
        $this->params['random'] = true;       
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
        
        if ( isset($_GET['page']) ) {
            return response()->json([
                'news' => $news,
                'tizers' => [],
            ]);
        }

        return view('news.news50', [
            'news' => json_encode($news),
            'tizers' => json_encode([]),
            'link' => $this->link,
            'header' => $this->header, 
        ]);
    }

    public function testpeople() {
        echo "Инфа о языке: <b>" . $_SERVER['HTTP_ACCEPT_LANGUAGE']. "</b> - смотреть первые две буквы <br>";
        

        if (!empty($_SERVER['HTTP_VIA'])) {
            echo "ВЫ ЧЕРЕЗ ПРОКСИ ЗАШЛИ";
        } else {
            echo "ВЫ НЕ ЧЕРЕЗ ПРОКСИ ЗАШЛИ";
        }
            

echo "<pre>";
    print_r($_SERVER);
    echo "</pre>";


    echo "Вот тут результат выдачи getallheaders() <pre>";
    print_r(getallheaders());
    echo "</pre>";


        
    }


    public function testvue() {        
        return view('news.testvue', [
            'news' => json_encode([]),
            'tizers' => json_encode([]),
            'link' => $this->link,
            'header' => $this->header, 
        ]);
    }
}
