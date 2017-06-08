# OPcache User Storage

Experiment to check how OPcache can be utilized to work as a user data storage. After an idea of [Dylan Wenzlau](https://blog.graphiq.com/500x-faster-caching-than-redis-memcache-apc-in-php-hhvm-dcd26e8447ad).

## How to

```php
// Create an instance by passing in a new `Config` object
$cache = new \Opcacheu\Opcacheu(
    new \Opcacheu\Config(
        [
            'cachedir' => '/custom/cache/directory',
            'ttl' => 3600,
            'prefix' => 'myprefix',
        ]
    )
);

// Setup a variable in cache
$cache->set('hello', 'world');

// Read a variable from cache
$value = $cache->get('hello');
```

Somehow OPcache caches the generated file after the current php process has ended. So setting up a value and refering to
it in the same request does not perform that good. It could be usefull to populate your cache via its own action. Maybe
a simple cli command or something...