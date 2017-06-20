<?php
require 'vendor/autoload.php';  
use Goutte\Client;

//target url
$url = "https://www.black-ink.org/";

$client = new Client();
$crawler = $client->request('GET', $url);
//find entry links
$links = $crawler->filter('h2.entry-title a')->extract(array('href'));

//initate
$filesize = 0.0;

$results = ['results' => []];

foreach ($links as $link) {
	$client = new Client();
	$crawler = $client->request('GET', $link);
	
	$filesize = calculate_filesize($crawler->html());
	$filesize += $filesize;
	
	$results['result'][] = [
		'url' => $link,
		'title' => $crawler->filter("h1.entry-title")->extract(array('_text'))[0],
		'meta description' => $crawler->filterXpath('//meta[@property="og:description"]')->attr('content'),
		'filesize' => $filesize.'Kb',
		];
}
$results['total'] = $total.'Kb';

//outputs json to screen
echo json_encode($results);

/**
 * calculates the filesize of a string in Kb
 * 
 * @param string content
 * @return float filesize
 *
 **/
function calculate_filesize($content) {
	return round(strlen($content) / 1024, 2);
}
