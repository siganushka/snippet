<?php

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

require './vendor/autoload.php';

$ruiwenUrl = 'https://www.iqce8.com/info/1.html';

$client = HttpClient::create();
$response = $client->request('GET', $ruiwenUrl);

$contents = $response->getContent();
// dd($contents);

$crawler = new Crawler($contents);
$crawler->filter('.swiper-slide')->each(function (Crawler $node, int $i) {
    // if ($i < 60) {
    //     return;
    // }

    $title = $node->filter('p')->text();
    $imgNode = $node->filter('p > .lazy');

    echo sprintf('<hr /><h1>问题: %s</h1><br />', $title);

    if ($imgNode->count()) {
        echo sprintf('图片: <img src="https://www.iqce8.com%s" /><br />', $imgNode->attr('data-original'));
    }

    $answersNode = $node->filter('.tabs > label > .lazy');
    if ($answersNode->count()) {
        $answersNode->each(function (Crawler $child, int $i) {
            echo sprintf('答案 %d: <p><img src="https://www.iqce8.com%s" /></p>', $i, $child->attr('data-original'));
        });
    } else {
        $node->filter('.tabs > label')->each(function (Crawler $child, int $i) {
            echo sprintf('答案 %d: <p>%s</p>', $i, $child->text());
        });
    }
});
