<?php

$imageTypes = [
    'gif'=> 'image/gif',
    'png'=> 'image/png',
    'jpeg'=> 'image/jpeg',
    'jpg'=> 'image/jpeg',
];

$fontTypes = [
    'eot' => 'x-font/eot',
    'woff' => 'x-font/woff',
    'woff2' => 'x-font/woff2',
    'ttf' => 'x-font/ttf',
];


$domain = $_SERVER['SERVER_NAME'];
$request = $_SERVER['REQUEST_URI'];
$file = substr($request, 1);

$ext = substr($file, strpos($file, '.') + 1);


$root = $_SERVER['DOCUMENT_ROOT'];
$path = $root . "/../landings/" . $domain;


$fullFilePath = $path . '/' . $file;

if (!is_file($fullFilePath)) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

if (isset($imageTypes[$ext])) {
    header('Content-type: ' . $imageTypes[$ext]);
    readfile($fullFilePath);
    exit;
}


if (isset($fontTypes[$ext])) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: inline; filename="'.basename($file).'"');
    header('Content-Length: ' . filesize($fullFilePath));
    readfile($fullFilePath);
    exit;
}

$mime = false;
switch($ext) {
    case "css": $mime = "text/css"; break;
    case "js": $mime  = "application/javascript"; break;
    case "xml": $mime  = "application/xml"; break;
    case "svg": $mime = "application/xml+svg"; break;
}

if ($mime) {
    header("Content-Type: ".$mime);   
}

readfile($fullFilePath);
exit;









