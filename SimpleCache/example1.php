<?php 
require('SimpleCache.php');

$cache = new SimpleCache('/path/to/cache/dir');

$feed = $cache->read('feed-phparch');

if(!$feed) {
    $feed = file_get_contents('http://www.phparch.com/feed/');
    $cache->save('feed-phparch', $feed, '5 minutes');
}

$xml = simplexml_load_string($feed);

foreach($xml->channel->item as $item) {
    echo '- <a href="', $item->link, '">', $item->title, '</a><br />', PHP_EOL;
}