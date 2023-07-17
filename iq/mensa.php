<?php

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

require './vendor/autoload.php';

$mensaUrl = 'https://test.mensa.no/';

$client = HttpClient::create();
$response = $client->request('GET', $mensaUrl);

$contents = $response->getContent();
// dd($contents);

$crawler = new Crawler($contents);
$crawler->filter('.question')->each(function (Crawler $node, int $i) {
    $questionNode = $node->filter('.standardQuestionImage');
    $answerNode = $node->filter('.standardAnswerImage');
    // dd($questionNode->count(), $answerNode->count());

    echo sprintf('<hr /><h1>问题: <img src="%s" /><br />', $questionNode->attr('src'));

    $answerNode->each(function (Crawler $child, int $i) {
        echo sprintf('答案 %d: <p><img src="%s" /></p>', $i, $child->attr('src'));
    });
});