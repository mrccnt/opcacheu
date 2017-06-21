<?php

include __DIR__ . '/../vendor/autoload.php';

// Same config as in 2-loop.php
$cache = new \Opcacheu\Opcacheu(
    new \Opcacheu\Config(
        [
            'ttl' => 10,
            'prefix' => 'example',
            'cachedir' => __DIR__ . '/example_cache_dir'
        ], true
    )
);

$cache->set('array1', array_fill(0, 1000, 'Hello World'));
$cache->set('array2', array_fill(0, 10, 'Hello World'));
$cache->set('string', 'Hello World');
