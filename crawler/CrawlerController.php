<?php

require_once '../vendor/autoload.php';
require_once 'crawler.php';

$startURL = $_POST['url'];

$crawler = new Crawler($startURL,3);
$urls = $crawler->run();

$encoded = json_encode(array_keys($urls));
header('Content-type: application/json');
exit($encoded);