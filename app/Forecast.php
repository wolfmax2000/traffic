<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Forecast extends Model
{
    public $table = 'forecast';

    private $_token;

    static $citiesMap = [
        'Москва' => 1,
        'Санкт-Петербург' => 1,
        'Ростов-на-Дону' => 3,
    ];

    protected $fillable = [
        'foreca_location_id',
        'sys_location_id',
        'json_data',        
    ];

    static function getData($city) {
        // Попытка найти в БД город        
        if (!isset(self::$citiesMap[$city])) {
            return false; 
        }
        
        $forecast = self::where('sys_location_id', self::$citiesMap[$city])
            ->where('updated_at', '>', now()->subHours(22)->toDateTimeString())
            ->first();

        if (!$forecast) { 
            if (!$forecast = self::where('sys_location_id', self::$citiesMap[$city])->first()) {
                $forecast = new Forecast();
            }
            
            $locations = self::locationSearch($city)->locations;
            if (!count($locations)) {
                return false;
            }
            
            $loc = $locations[0];
           
            $forecast->foreca_location_id = $loc->id;
            $forecast->sys_location_id = self::$citiesMap[$city];
            $data = new \stdClass();
            $current = self::current($loc->id);
            if (!$current) {
                return false;
            }
            $data->current = $current;
            

            $daily = self::daily($loc->id);
            if (!$daily) {
                return false;
            }
            $data->daily = $daily;
             
            $hourly = self::hourly($loc->id);
            if (!$hourly) {
                return false;
            }
            $data->hourly = $hourly;

            $forecast->json_data = json_encode($data);
            $forecast->updated_at = date("Y-m-d H:i:s");
            $forecast->save();                         
        } else {
            $data = json_decode($forecast->json_data);
        }
        
        return array_merge(self::format($data), [
            'sys_location_id' => self::$citiesMap[$city]
        ]);
    }

    private static function format($data) {
        $clearData = [];
        $days = [];
        $allHours = $data->hourly->forecast;

        foreach ($data->daily->forecast as $day) {            
            $day->hours = array_filter($allHours, function($h) use ($day) {                
                return $day->date === date("Y-m-d", strtotime($h->time)  + (3600 * 3) );
            });            
            $days[$day->date] = $day;
        }        
        $clearData['current'] = $data->current->current;
        $clearData['forecast'] = $days;   
        
        return $clearData;
    }


    private static function locationSearch($query) {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://pfa.foreca.com/api/v1/location/search/' . $query . '?lang=ru&token=' . self::getToken(), []);
        if ($response->getStatusCode() != 200) {
            return false;
        }

        return json_decode($response->getBody()->getContents());
    }

    private static function current($foreca_location_id) {
        $client = new \GuzzleHttp\Client();
        $url = 'https://pfa.foreca.com/api/v1/current/' . $foreca_location_id . '?lang=ru&token=' . self::getToken();        
        $response = $client->request('GET', $url, []);
        if ($response->getStatusCode() != 200) {
            return false;
        }

        return json_decode($response->getBody()->getContents());
    }

    private static function daily($foreca_location_id) {
        $client = new \GuzzleHttp\Client();
        $url = 'https://pfa.foreca.com/api/v1/forecast/daily/' . $foreca_location_id . '?periods=7&token=' . self::getToken();        
        $response = $client->request('GET', $url, []);
        if ($response->getStatusCode() != 200) {
            return false;
        }

        return json_decode($response->getBody()->getContents());
    }

    private static function hourly($foreca_location_id) {
        $client = new \GuzzleHttp\Client();
        $url = 'https://pfa.foreca.com/api/v1/forecast/hourly/' . $foreca_location_id . '?periods=168&token=' . self::getToken();        
        $response = $client->request('GET', $url, []);
        if ($response->getStatusCode() != 200) {
            return false;
        }

        return json_decode($response->getBody()->getContents());
    }

    private static function getToken() {        
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://pfa.foreca.com/authorize/token', [
            'form_params' => [
                'user' => 'orusit',
                'password' => 'E7GKBLl4LUqcv7HqHD', 
                'expire_hours' => 1, // default
            ]            
        ]);

        if ($response->getStatusCode() != 200) {
            return false;
        }

        $jsonContents = $response->getBody()->getContents();
        $contents = json_decode($jsonContents);

        return $contents->access_token;        
    }




}



