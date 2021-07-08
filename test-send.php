#!/usr/bin/env php
<?php

$url = 'https://fcm.googleapis.com/fcm/send';
$YOUR_API_KEY = 'AAAAcPZZIq8:APA91bHFhDcGtalXH5Dny0AfYJoD6dl2Vt382wZPTlM8Ajj-8HbcXCmMn4d8LOYOKepX0yBNkyVyp5zaMYDkv3asYuMZjFaLPV76oG4O3J2AS5uqoeZdfbOOLQZkDbp2Rn7q-Z5u-q6l'; // Server key

$registration_ids = [
    'd9rKhZF1Oag:APA91bFLn3pyNJX9U0oAyx-bg9FEIY8ZbaoZWVSW9v3RSHlVoUGwELvnbJbY1CMDlERNVQ30IDUidUROYZ23qntpm5zdV4h-fxUu2h7zBPJ8RCHCpODPY9pFo49eY08GPGNx68T7LCXi',
    'd9rKhZF1Oag:APA91bFLn3pyNJX9U0oAyx-bg9FEIY8ZbaoZWVSW9v3RSHlVoUGwELvnbJbY1CMDlERNVQ30IDUidUROYZ23qntpm5zdV4h-fxUu2h7zBPJ8RCHCpODPY9pFo49eY08GPGNx68T7LCss',
    'cGyvo640bhI:APA91bEvWQQ3eEwC5dm7oGe0IwNckkelKwptsX920twg2AkucjPIc7lhqj01QfRX-mYflL8FVeBOXPK3MlVYMYH1B72CeZB1L_lUu5-207IcESA46lnRBJf3AIhoRV46jB2L2jg6t4bQ'
];

$request_body = [
    'registration_ids' => $registration_ids,
    'notification' => [
        'title' => 'Ералаш',
        "style" => "inbox",
        'body' => sprintf('Начdало в %s.', date('H:i')),
        'icon' => 'https://informerspro.ru/storage/63/conversions/5e3a8bba1f013_oF4mYcO7uiEM8QF-thumb.jpg',
        "image" => "https://informerspro.ru/storage/63/conversions/5e3a8bba1f013_oF4mYcO7uiEM8QF-thumb.jpg",
        'click_action' => 'http://eralash.ru/',
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
curl_close($ch);

echo $response;