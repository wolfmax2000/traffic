#!/usr/bin/env php
<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://gelonews.ru/admin_api/v1/report/build');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: a4fbc39dd2153f1af4ef6a884218a085'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$params = [
    //'range' => [
        //'from' => '2017-09-10',
        //'to' => '2017-09-12',
        //'timezone' => 'Europe/Madrid'
    //],
    'limit' => 1,
    'grouping' => ['creative_id'],
    //'metrics' => ['clicks', 'bot_share', 'cr'],
    'filters' => [
        //['name' => 'campaign_id', 'operator' => 'EQUALS', 'expression' => 4],
        ['name' => 'creative_id', 'operator' => 'EQUALS', 'expression' => "push_id"],
    ]
];
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
echo curl_exec($ch);