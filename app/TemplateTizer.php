<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TemplateTizer extends Model
{
    public $timestamps = false;
    public $table = 'template_tizer';

    protected $fillable = [
        'tizer_id',
        'tempalte_id', 
        'views',
        'clicks'
    ];

    public static function recalc() {
        $views = DB::table('template_tizer_views')
            ->where('time', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 14 DAY)'))
            ->count();
            
        echo "Лишних VIEWS - $views \n";
        $limit = intval($views / 100);
        echo "Лимит - $limit \n";

        if ( $limit > 1 )
        for ( $i=0; $i < 100; $i++) {
                echo "$i \n";                
                DB::statement('DELETE FROM template_tizer_views WHERE time < DATE_SUB(NOW(), INTERVAL 14 DAY) LIMIT '. $limit);
        }

        $clicks = DB::table('template_tizer_clicks')
            ->where('time', '<', DB::raw('DATE_SUB(NOW(), INTERVAL 14 DAY)'))
            ->count();
            
        echo "Лишних CLICKS - $clicks \n";
        $limit = intval($clicks / 100);
        echo "Лимит - $limit \n";

        if ( $limit > 1 )
        for ( $i=0; $i < 100; $i++) {
            echo "$i \n";                
            DB::statement('DELETE FROM template_tizer_clicks WHERE time < DATE_SUB(NOW(), INTERVAL 14 DAY) LIMIT '. $limit);
        }
        
        $templates = Template::all();
        foreach ($templates as $t) {
            echo "@@@ \n";
            $catIds = [];
            foreach ( $t->categories as $c) {
                $catIds[] = $c->id;
            }
            $tizerIds = [];
            $tizers = Tizer::select('id')->whereIn('category_id', $catIds)->get();
            foreach ( $tizers as $tz) {
                echo "tz:" . $tz->id . "\n";
                $ct = TemplateTizer::where('template_id', $t->id)
                    ->where('tizer_id', $tz->id)->first();

                if ( !$ct ) {
                    $ct = new TemplateTizer();
                    $ct->tizer_id = $tz->id;
                    $ct->template_id = $t->id;
                }

                $views = DB::table('template_tizer_views')
                    ->where('template_id', $t->id)
                    ->where('tizer_id', $tz->id)
                    ->where('time', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$t->stat_days.' DAY)'))
                    ->count();

                $clicks = DB::table('template_tizer_clicks')
                    ->where('template_id', $t->id)
                    ->where('tizer_id', $tz->id)
                    ->where('time', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$t->stat_days.' DAY)'))
                    ->count();

                $ct->views = $views;
                $ct->clicks = $clicks;
                $ct->save();                 
            }
            
            
        }
    }
}
