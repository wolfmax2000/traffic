<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Stat;
use App\Sids;
use App\Sites;

use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends Controller
{
  
   
    public function index()
    {
        abort_if(Gate::denies('tizer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sids = Sids::all();
        $current_sid = isset($_GET['current_sid']) ? intval($_GET['current_sid']) : '';
        $sites = Sites::all();
        $current_site = isset($_GET['current_site']) ? intval($_GET['current_site']) : '';

        $stats_collect = Stat::select(DB::raw('sites.name as site_name, sids.name as sid_name, count(sids.name) cnt, sum(is_unique) as uniq, avg(time) as avg_time, sum(news_views) views, sum(tizer_clicks) clicks, count(IF(time<15, 1, NULL)) otkaz'))
            ->from('stat') 
            ->leftJoin('sids', 'sids.id', '=', 'stat.sid')
            ->leftJoin('sites', 'sites.id', '=', 'stat.site')
            ->groupBy(DB::raw('sid_name, site_name'));

        if ( isset($_GET['current_sid']) ) {
            $stats_collect->where('sid', $_GET['current_sid']);
        }

        if ( isset($_GET['current_site']) ) {
            $stats_collect->where('site', $_GET['current_site']);
        }
        
        $stats = $stats_collect->get();


        return view('admin.stats.index', compact('stats', 'sids', 'current_sid', 'sites', 'current_site'));
    }

    
}


