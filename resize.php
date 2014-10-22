<?php
$startTime = microtime(true);
$params = arguments($argv);
if (empty($params['width']) || empty($params['height'])) {
    echo "\nIncorrect result size params".PHP_EOL;exit;
}

if (empty($params['originalDirectory'])) {
    echo "\nIncorrect input params".PHP_EOL;exit;
}
if (!$params['originalDirectory']) {
    echo "\nDirectory does not exist".PHP_EOL;exit;
}

$resultDirectory = $params['originalDirectory'].'/resize-'.$params['width'].'x'.$params['height'];
if (!file_exists($resultDirectory)) {
    mkdir($resultDirectory, 0777, true);
}
$files = scandir($params['originalDirectory']);
$countAll = 0;
foreach ($files as $file) {

    $fileOriginalPath = $params['originalDirectory'].'/'.$file;
    $imageSize = getimagesize($fileOriginalPath);
    if (empty($imageSize) || $imageSize['mime'] != 'image/jpeg' ) {
        continue;
    }

    $countAll++;
    $fileResultPath = $resultDirectory.'/'.$file;

    $convertCode = 'convert "'.$fileOriginalPath.'" -resize '.$params['width'].'x'.$params['height'].' -quality 100 "'.$fileResultPath.'"';

    exec($convertCode);
    echo PHP_EOL.$file." done";
}

$finishTime = microtime(true) - $startTime;
$maxMemory = (memory_get_peak_usage(true)/1024/1024);
$resultData = 'Script time: '.$finishTime.'; max memory: '.$maxMemory.'MB; Images: '.$countAll . ';';
echo PHP_EOL.'==============================================================================================' . PHP_EOL;
echo $resultData . PHP_EOL . PHP_EOL . PHP_EOL;


function arguments($argv) {
    $_ARG = array();
    foreach ($argv as $arg) {
        if (ereg('--([^=]+)=(.*)',$arg,$reg)) {
            $_ARG[$reg[1]] = $reg[2];
        } elseif(ereg('-([a-zA-Z0-9])',$arg,$reg)) {
            $_ARG[$reg[1]] = 'true';
        }

    }
    return $_ARG;
}