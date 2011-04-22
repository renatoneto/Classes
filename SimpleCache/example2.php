<?php
require('SimpleCache.php');

$cache = new SimpleCache('/path/to/cache1/dir');

$content = $cache->read('cache1');

if(!$content) {
    $content = mt_rand(1, 99999999);
    $cache->save('cache1', $content, '1 minute');
}

echo 'Cache 1: ', $content, '<br />';

$cache = new SimpleCache('/path/to/cache2/dir');
$content = $cache->read('cache2');

if(!$content) {
    $content = mt_rand(1, 99999999);
    $cache->save('cache2', $content, '2 minutes');
}

echo 'Cache 2: ', $content;