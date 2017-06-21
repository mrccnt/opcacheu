<?php

include __DIR__ . '/../vendor/autoload.php';

use \Opcacheu\Opcacheu;
use \Opcacheu\Config;

// Same config as in 1-setup.php
$cache = new Opcacheu(
    new Config(
        [
            'ttl' => 10,
            'prefix' => 'example',
            'cachedir' => __DIR__ . '/example_cache_dir'
        ], true
    )
);


$ostart = microtime(true);

for ($x=0; $x<=20; $x++) {
    sleep(1);
    foreach (['array1', 'array1', 'array2', 'array2', 'string', 'string'] as $name) {
        $stamp = microtime(true);
        $value = $cache->get($name);
        $stop = microtime(true);
        if (is_null($value)) {
            echo PHP_EOL;
            break 2;
        }
        echo sprintf('%s:            %s', $name, (string) ($stop - $stamp)) . PHP_EOL;
    }
    echo PHP_EOL;
}

$ostop = microtime(true);
echo sprintf('Overall:      %s', (string) ($ostop - $ostart)) . PHP_EOL;
