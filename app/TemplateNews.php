<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TemplateNews extends Model
{
    public $timestamps = false;
    public $table = 'template_news';

    protected $fillable = [
        'news_id',
        'tempalte_id', 
        'views',
        'clicks'
    ];

    public static function recalc() {
        $views = DB::table('template_news_views')
            ->where('time', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 14 DAY)'))
            ->count();
            
        echo "Лишних VIEWS - $views \n";
        $limit = intval($views / 100);
        echo "Лимит - $limit \n";

        if ( $limit > 1 )
        for ( $i=0; $i < 100; $i++) {
                echo "$i \n";                
                DB::statement('DELETE FROM template_news_views WHERE time < DATE_SUB(NOW(), INTERVAL 14 DAY) LIMIT '. $limit);
        }

        $clicks = DB::table('template_news_clicks')
            ->where('time', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 14 DAY)'))
            ->count();
            
        echo "Лишних CLICKS - $clicks \n";
        $limit = intval($clicks / 100);
        echo "Лимит - $limit \n";

        if ( $limit > 1 )
        for ( $i=0; $i < 100; $i++) {
            echo "$i \n";                
            DB::statement('DELETE FROM template_news_clicks WHERE time < DATE_SUB(NOW(), INTERVAL 14 DAY) LIMIT '. $limit);
        }


        $templates = Template::all();
        $news = News::all();
        foreach ($templates as $t) {
            echo "@@@ \n";                        
            foreach ( $news as $n) {
                echo "nw:" . $n->id . "\n";
                $ct = TemplateNews::where('template_id', $t->id)
                    ->where('news_id', $n->id)->first();

                if ( !$ct ) {
                    $ct = new TemplateNews();
                    $ct->news_id = $n->id;
                    $ct->template_id = $t->id;
                }

                $views = DB::table('template_news_views')
                    ->where('template_id', $t->id)
                    ->where('news_id', $n->id)
                    ->where('time', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$t->stat_days.' DAY)'))
                    ->count();

                $clicks = DB::table('template_news_clicks')
                    ->where('template_id', $t->id)
                    ->where('news_id', $n->id)
                    ->where('time', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$t->stat_days.' DAY)'))
                    ->count();

                $ct->views = $views;
                $ct->clicks = $clicks;
                $ct->save();                 
            }
            
            
        }
    }
}
