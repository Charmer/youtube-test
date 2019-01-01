<?php
/**
 * Created by PhpStorm.
 * User: timur
 * Date: 01.01.2019
 * Time: 19:28
 */

require_once __DIR__ . '/vendor/autoload.php';
$client = new Google_Client();
$api_key = "";
$client->setDeveloperKey($api_key);
$youtube = new Google_Service_YouTube($client);

//API YouTube не пзволяет сортировать выдачу по нескольким параметрам
//Так что сначала найдем самые популярные видеоролики, потом получим кол-во их просмотров и отсортируем по ним
$response = $youtube->search->listSearch('snippet',
    array(
        'q' => $_GET['q'],
        'maxResults' => 20,
        'relevanceLanguage' => 'ru',
        'type' => 'video',
        'order' => 'date'
    ));
$items = $response->getItems();
$ids = array();
foreach ($items as $item) {
    $ids[] = $item->id->videoId;
}

$response = $youtube->videos->listVideos('snippet, statistics', [
    'id' => implode(",", $ids),
]);
//Соберем массив для сортировки видео
$sort = array();
$items = $response->getItems();
foreach ($items as $item) {
    $sort[$item->id] = [
        'id' => $item->id,
        'views' => $item->statistics->viewCount,
        'title' => $item->snippet->title,
        'author' => $item->snippet->channelTitle,
        'published' => date("d.m.Y H:i", strtotime($item->snippet->publishedAt)),
    ];
}
usort($sort, function ($a, $b) {
    return $a['views'] < $b['views'];
});
header('Content-Type: application/json');
echo json_encode(['q'=>$_GET['q'], "result" => $sort]);
