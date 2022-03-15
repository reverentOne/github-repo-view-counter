<?php

$timestamp = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: $timestamp");
header("Last: $timestamp");
header("pragma: no-cashe");
header("Cache-Control: no-cache, must-revalidate");

header("content-type: image/svg+xml");

function incrementFile($filename) : int 
{
    if(file_exists($filename)) {
        $fp = fopen($filename, "r+") or die("Faild to open the file.");
        flock($fp, LOCK_EX);
        $count = fread($fp, filesize($filename)) +1;
        ftruncate($fp, 0);
        fseek($fp, 0);
        fwrite($fp, $count);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    else {
        $count = 1;
        file_put_contents($filename, $count);
    }
    return $count;
}

function shortNumber($num)
{
    $i = 0;
    $units = ['', 'K', 'M', 'B'];
    if ($num >= 10000){
        for ($i; $num >= 1000; $i++){
            $num /= 1000;
        }
    }
    return round($num, 1) . $units[$i];
}

$message = incrementFile("views.txt");

$params = [
    "label" => "Page Views",
    "logo" => "github",
    "message" => shortNumber($message),
    "color" => "informational",
    "style" => "for-the-badge"
];

$url = "https://img.shields.io/static/v1?" . http_build_query($params);

echo file_get_contents($url);