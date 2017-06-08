# OPcache User Storage

Experiment to check how OPcache can be utilized to work as a user data storage. After an idea of [Dylan Wenzlau](https://blog.graphiq.com/500x-faster-caching-than-redis-memcache-apc-in-php-hhvm-dcd26e8447ad).

## Configure Cache

```php
$cache = new \Opcacheu\Opcacheu(
    new \Opcacheu\Config(
        [
            'cachedir' => '/custom/cache/directory',
            'ttl' => 3600,
            'prefix' => 'myprefix',
        ]
    )
);
```

## Store Value

```php
$cache->set('hello', 'world');
```

## Retrieve Value

```php
$value = $cache->get('hello');
```