<?php

namespace App\Http\Controllers;

use App\Tizer;
use App\TizerTest;
use App\Stat;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class TizerController extends FrontController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $firstSkip = 0;
        $nearRazgon = false;
        
        
        $foundTestTizer = false;        
        if ( $this->abtest ) {
            $foundTestTizer = Tizer::algo(array_merge($this->params, ['tizer_test_id' => $this->abtest->tizer_id]))
                ->first();                
        }

        if ( !$foundTestTizer ) {
            $razgon = Tizer::algo(array_merge($this->params, ['razgon' => true]))->first();
        } else {            
            $razgon = $this->abtest->getNextState();
        }

        if ( $razgon ) {     
            $this->params['excludeIds'] = array();
            $this->params['excludeIds'][] = $razgon['id']; 
            
            $firstSkip = 1;
            $nearRazgon = Tizer::algo($this->params)->take($firstSkip)->get();            
        } 

        $skip = isset( $_GET['skip'] ) ? intval($_GET['skip']) : $firstSkip;
        $page = isset( $_GET['page'] ) ? intval($_GET['page']) : 0;
        $rows = Tizer::getRows($skip, $page, $this->params);

        return view('tizer.index', [
            'nearRazgon' => $nearRazgon,
            'razgon' => $razgon,
            'rows' => $rows,            
            'nextUrl' => '/tizers?page=' .  $page . '&skip=' . $skip . '&' . $this->link,
            'link' => $this->link,
            'isMobile' => $this->isMobile,
            'params' => $this->params,
            'short' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tizer  $tizer
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $tizer = Tizer::find($id);
        $tizer->clickUp($this->params['template_id']);

        if ( !is_null($this->stat) ) {            
            $this->stat->increment('tizer_clicks');
        }

        return Redirect::to($tizer->ext_link . '?' . $this->link);
    }

    public function click_test(int $id, string $click_by)
    {
        $tizerTest = TizerTest::find($id);
        $tizerTest->tizer->clickUp($this->params['template_id']);        
        $tizerTest->increment($click_by == 'a' ? 'click_a' : 'click_b');        
        
        if ( !is_null($this->stat) ) {            
            $this->stat->increment('tizer_clicks');
        }

        return Redirect::to($tizerTest->tizer->ext_link . '?' . $this->link);
    }

    
}
