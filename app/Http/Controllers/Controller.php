<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $countries = [
        'Россия',
        'Азербайджан',
        'Армения',
        'Беларусь',
        'Казахстан',
        'Кыргызстан',
        'Молдова',
        'Таджикистан',
        'Узбекистан',
        'Украина',
        'Болгария',
        'Украина',
        'Испания',
        'Германия',
        'Румыния',
        "Греция",
        "Венгрия",
        "Мексика",
        "Испания",
        "Великобритания",
        "Тайланд",
        "Индонезия",
        "США",
        'Вьетнам',
    ];

    public $devices = [
        'all',
        'mobile',
        'desktop'
    ];

    public $isocountries = [
        'RU' => "Россия",
        'BG' => "Болгария",
        'UA' => "Украина",
        'AZ' => 'Азербайджан',
        'KZ' => 'Казахстан',
        'KG' => 'Кыргызстан',
        'MD' => 'Молдова',
        'TJ' => 'Таджикистан',
        'RO' => 'Румыния',
        'UZ' => 'Узбекистан',
        'ES' => 'Испания',        
        'DE' => 'Германия',
        'GR' => "Греция",
        'HU' => "Венгрия",
        "MX" => "Мексика",
        "ES" => "Испания",
        "GB" => "Великобритания",
        "TH" => "Тайланд",
        "ID" => "Индонезия",
        'US' => "США",
        'VN' => 'Вьетнам',
    ];

    public $utms = [
        'template_id',
        'utm_term',
        'utm_target',
        'utm_source',
        'utm_position',
        'utm_placement',
        'utm_network',
        'utm_match',
        'utm_creative',
        'utm_campaign',
        'sub1',
        'sub2',
        'sub3',
        'sub4',
        'sub_id_1',
        'sub_id_2',
        'sub_id_3',
        'sub_id_4',
        'source',
        'external_id',
        'currency',
        'creative_id',
        'ad_campaign_id',
        'yclid',
        'subid1',
        'subid2',
        'subid3',
        'subid4',
        'click_id',
        'click_id_1',
        'click_id_2',
        'click_id_3',
        'sub_id_5',
        'sub_id_6',
        'sub_id_7',
        'sub_id_8',
        'sub_id_9',
        'sub_id_10',
        'uid',
        'sid',
        'sid1',
        'sid2',
        'sid3',
        'sid4',
        'sid5',
        'sid6',
        'sid7',
        'sid8',
        'sid9',
        'sid10',
        'sid11',
        'sid12',
        'sid13',
        'sid14',
        'sid15',
        'vcode',
        'data',
        'sub_id1',
        'affsub',
        'pbid',

    ];
}
