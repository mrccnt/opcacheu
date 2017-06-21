# OPcache User Storage

Experiment to check how OPcache can be utilized to work as a user data storage. After an idea of [Dylan Wenzlau](https://blog.graphiq.com/500x-faster-caching-than-redis-memcache-apc-in-php-hhvm-dcd26e8447ad).

## Install, Test and Build

```bash
composer install    # Of course... Install dependencies...
composer reports    # Execute tests and create reports (dist/report)
composer build      # Build production zip file
```

## How to

```php
// Create an instance by passing in a new `Config` object
$cache = new \Opcacheu\Opcacheu(
    new \Opcacheu\Config(
        [
            'cachedir' => '/custom/cache/directory',
            'ttl' => 3600,
            'prefix' => 'myprefix',
            'autocreate' => true,
        ]
    )
);

// Setup a variable in cache
$cache->set('hello', 'world');

// Read a variable from cache
$value = $cache->get('hello');
```

## How does it work / Timings

Keep in mind how OPcache handles files. Every time the php process handles a file via autoloading or `include` or
something, it creates a cache entry for that file in its storage. The next time php tries to load the file, OPcache
serves the precompiled file out of its storage.