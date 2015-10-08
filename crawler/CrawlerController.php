<?php

require_once '../vendor/autoload.php';
require_once 'crawler.php';
require_once 'CKMoz.php';
require_once 'auth/auth.php';

$startURL = $_POST['url'];
$urls = [];

$crawler = new Crawler($startURL,3);
$urls = $crawler->run();

var_dump($urls);die();
$encoded = json_encode($urls);
header('Content-type: application/json');
exit($encoded);

if(!is_array($urls))
    throw new Exception("There's no URL's to make the consult.");

$groups = array_chunk($urls, 10);

$metricQ = new CKMoz(ACCESS_ID, SECRET_KEY);
$cols = array('title','canonURL','ExEquityLinks','links','mozRankURL','mozRankSubDomain','httpCode','pageAuth','domainAuth');

$result = array();

foreach($groups as $group) {
    var_dump($group);exit();
    $rs = $metricQ->batchedQuery($group,$cols);

    // if the result is an error of authentication
    if($rs->code == 401) {
        header('Content-type: application/json');
        exit($rs->data);
    }
    // print_r($rs->code . '\n');
    // var_dump($rs->data);die();
    $data = json_decode($rs->data, true);
    var_dump($data);exit();
    foreach($group as $key => $link) {
        $result[] = array('url' => $link, 'data' => $data[$key]);
    }
    //$result[] = $rs->data;
}

$encoded = json_encode($result);
header('Content-type: application/json');
exit($encoded);