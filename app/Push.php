<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Push extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;
    public $table = 'push_push';

    const D_MOBILE = 'mobile';
    const D_DESKTOP = 'desktop';
    const D_ALL = 'all';
    static $devices = [
        self::D_MOBILE => 'Мобильники',
        self::D_DESKTOP => 'Десктоп',
        self::D_ALL => 'Все'
    ];

    const TT_MAIN = 'main';
    const TT_NEW = 'new';
    const TT_ADULT = 'adult';
    static $top_types = [
        self::TT_MAIN => 'Основной',
        self::TT_NEW => 'Новые подписчики',
        //self::TT_ADULT => 'Развлекатальный'
    ];

    const PT_NEWS = 'news';
    const PT_OFFERS = 'offers';
    const PT_ADULTS = 'adults';
    static $push_types = [
        self::PT_NEWS => 'Новостной',
        self::PT_OFFERS => 'Товарный',
        self::PT_ADULTS => 'Адалт/Шок'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'desc',
        'domen',   
        'url',
        'geo',
        'device',
        'top_type',
        'push_type',
        'template_id',
        'clicks',
        'views',
        'cpc',
        'status',
        'limit',

        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function send() {
        $sid8sQuery = PushClient::select('sid8')->where('country', $this->geo);
        if ( $this->device !== 'all' ) {
            $sid8sQuery->where('isMobil', $this->device == 'mobile');
        }    
        $sid8s = $sid8sQuery->orderBy('sid8','asc')->groupBy('sid8')->get();
        foreach ($sid8s as $sid8obj) {
            $sid8 = $sid8obj->sid8;            
            $cond = PushClient::where('country', $this->geo)->where('sid8', $sid8);
            if ( $this->device !== 'all' ) {
                $cond->where('isMobil', $this->device == 'mobile');
            }

            if ( $this->domen !== 'all_domains' ) {
                $cond->where('domen', $this->domen);
            }

            // top_type
            if ($this->top_type == self::TT_MAIN) { // тут получают только те подписчики, который подписались более 10 дней назад
                $cond->where('created_at', '<=', Carbon::now()->subDays(10)->toDateTimeString());
            }

            // top_type
            if ($this->top_type == self::TT_NEW) { // тут получают только новые подписчики, который подписались менее чем 10 дней назад
                $cond->where('created_at', '>', Carbon::now()->subDays(10)->toDateTimeString());
            }


            $clients = $cond->get();
            
            $ids = [];
            foreach ( $clients as $c ) {
                $ids[] =  $c['client_id'];
                if (count($ids) > 999 ) {
                    $this->send1000($ids, $sid8);
                    $ids = [];
                }
            }
            //echo count($ids);die();
            if ( count($ids) > 0 ) {
                $this->send1000($ids, $sid8);
            }
            
        }

        $this->status = 'done';
        $this->save();

    }

    public function send1000($registration_ids, $sid8) {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $YOUR_API_KEY = 'AAAAcPZZIq8:APA91bHFhDcGtalXH5Dny0AfYJoD6dl2Vt382wZPTlM8Ajj-8HbcXCmMn4d8LOYOKepX0yBNkyVyp5zaMYDkv3asYuMZjFaLPV76oG4O3J2AS5uqoeZdfbOOLQZkDbp2Rn7q-Z5u-q6l'; // Server key

        $request_body = [
            'registration_ids' => $registration_ids,
            'notification' => [
                'title' => $this->title,
                "style" => "inbox",
                'body' => $this->desc,
                'icon' => 'https://informerspro.ru'. $this->icon->getUrl('icon_thumb'),
                "image" => 'https://informerspro.ru'. $this->image->getUrl('thumb'),
                'click_action' => $this->getFullUrl($sid8),
            ],
        ];
        $fields = json_encode($request_body);

        $request_headers = [
            'Content-Type: application/json',
            'Authorization: key=' . $YOUR_API_KEY,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $response = curl_exec($ch);        
        $response = json_decode($response);
      

        if ( $response->failure > 0 ) {
            $toRemoveIds = [];
            foreach ( $response->results as $key => $r) {
                if ( isset($r->error) ) {
                    if ( $r->error == 'InvalidRegistration' || $r->error == 'NotRegistered' ) {
                        $toRemoveIds[] = $registration_ids[$key];
                    }
                }
            }

            DB::table('push_clients')->whereIn('client_id', $toRemoveIds)->delete();
        }
        
        curl_close($ch);
    }

    public function getFullUrl($sid8) {                     
        return  $this->url . ( strpos($this->url, '?') ? '&' : '?' ) . 'sid9=' . $this->id . '&sid8='. $sid8;        
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->width(492)->height(328);
        $this->addMediaConversion('icon_thumb')->width(192)->height(192);
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

    public function getIconAttribute()
    {
        $file = $this->getMedia('icon')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('icon_thumb');
        }

        return $file;
    }

}
