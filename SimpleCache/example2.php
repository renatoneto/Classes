<?php
require('SimpleCache.php');

$cache1 = new SimpleCache('/path/to/cache1/dir');

$content = $cache1->read('cache1');

if(!$content) {
    $content = mt_rand(1, 99999999);
    $cache1->save('cache1', $content, '1 minute');
}

echo 'Cache 1: ', $content, '<br />';

$cache2 = new SimpleCache('/path/to/cache2/dir');
$content = $cache2->read('cache2');

if(!$content) {
    $content = mt_rand(1, 99999999);
    $cache2->save('cache2', $content, '2 minutes');
}

echo 'Cache 2: ', $content;