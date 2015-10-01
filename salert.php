<?php
require __DIR__ . '/vendor/autoload.php';

use Parse\ParseObject;
use Parse\ParseClient;

ParseClient::initialize( 'UfiwYXJ6Ud5p1HMjojrjDkXSLcQlMguchxq4ZO4Z', 'vqWoR2egc5WHuM1hGeXFSqLOBa5qMcGFCrmTPX1t', 'tkb4ZUsrbDMCkHNSrIwIJRvlXSqddiW4MMxtHchz' );

$settings = array(
    'oauth_access_token' => "34067765-q4viaPua4ELsW8W6qdS3sqb97w631Z4qqK2g6etkj",
    'oauth_access_token_secret' => "hJYYGdEjtNCEMsXVEqe510xXxxzaECjC5si9PrKgNVjb2",
    'consumer_key' => "YmqAfcbKF277aoAXInHzzumP8",
    'consumer_secret' => "s9vKqDzN4vXYXiQYVGavCT4WIFbMrfDblA39fIeuN5GjV93xfr"
);

$accounts = [
	'Seguridad_Ec',
	'navasveracesar',
	'IGecuador',
];
$words = [
	' naranja',
	' roja',
//	'evac',
//	'ceniza',
//	'explo',
];
$exclude_words = [
	'simula',
	'conocieron',
	'seguimos en alerta amarilla',
	'nunca el #VolcánCotopaxi ha sido declarado en alerta naranj',
	'no ha sido declarado en alerta naranja',
	'no esta en alerta naranja',
	'no estamos en alerta naranja',
	'continua la alerta amarilla',
	'Naranja la evacuación es mandatoria para personas con discapacidad y tercera edad; voluntaria el resto. Alerta roja todos evacua',
	'alexysac es imposible de determinar'
	'en naranja',
	'en roja',
];

$alert_tweets = array();

$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);

foreach ($accounts as $account) {
	searchAccount($account);
}

header('Content-Type: application/json');
echo json_encode($alert_tweets);

function searchAccount($account) {
	global $twitter, $requestMethod, $url, $settings;
	$getfield = "?screen_name={$account}";
	$tweets = $twitter->setGetfield($getfield)
        	     	->buildOauth($url, $requestMethod)
             		->performRequest();
	$tweets = json_decode($tweets);
	foreach ($tweets as $tweet) {
		testWords($tweet);
	}
}

function testWords($tweet) {
	global $words, $exclude_words, $alert_tweets;
	foreach ($words as $word) {
		if (stripos($tweet->text, $word) !== false) {
			$alert = true;
			foreach($exclude_words as $ew) {
				if (stripos($tweet->text, $ew) !== false) {
					$alert = false;
					$break;
				}
			}
			if ($alert) {
				$alert_tweets[] = $tweet;
			}
		}
	}
}

