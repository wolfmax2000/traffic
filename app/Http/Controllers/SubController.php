<?php

namespace App\Http\Controllers;

use App\PushClient;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Torann\GeoIP\GeoIP;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Intervention\Image\Gd\Decoder;

class SubController extends Controller
{
    public function subscriber(Request $request) {
        if ( !$request->has('token') ) {
            return response()->json(['error' => 'noToken']);
        }

        if ( PushClient::where('client_id', $request->get('token'))->exists() ) {
            return response()->json(['error' => 'already']);
        }
    
        $agent = new \Jenssegers\Agent\Agent; 
        $reader = new \GeoIp2\Database\Reader(__DIR__ . '/../../../GeoLite2-City.mmdb');
        $record = $reader->city($_SERVER['REMOTE_ADDR']);

        $city = null;
        if ( $record) { // это передаём для макроса [CITI]   
            $city = $record->city->names['ru'];                
        }

        $country = null;
        // это передаём для алгоритма выдачи
        if ( $record && isset($this->isocountries[$record->country->isoCode]) ) {
            $country = $this->isocountries[$record->country->isoCode];
        }
        
        $pushClient = new PushClient;
        $pushClient->client_id = $request->get('token');
        $pushClient->city = $city;
        if ( $request->has('domen') ) {
            $pushClient->domen = $request->get('domen');
        }
        if ( $request->has('sid8') ) {
            $pushClient->sid8 = $request->get('sid8');
        }

        $pushClient->country = $country;
        $pushClient->isMobil = $agent->isMobile();
        try {
            $save = $pushClient->save();
        }
        catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
        
        return response()->json([
            'request' => $request,
            'isMobil' => $agent->isMobile(),
            'ip' => $_SERVER['REMOTE_ADDR'], 
            'country' => $country,
            'ico' => $record->country->isoCode,
            'city' => $city,
        ]);
    }    
   
}
