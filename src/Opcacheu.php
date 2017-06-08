<?php

namespace Opcacheu;

/**
 * Class Opcacheu
 * @package Opcacheu
 */
class Opcacheu
{
    /**
     * @var string  Content schema of cache file
     */
    protected $contentSchema = '<?php $ttl = %s; $val = %s;';

    /**
     * @var string  Name schema for cache file
     */
    protected $nameSchema = '%s/%s_%s.php';

    /**
     * @var Config  Cache configuration
     */
    protected $cfg;

    /**
     * Construct by passing in a config object
     *
     * @param Config $config    Cache configuration
     */
    public function __construct($config)
    {
        $this->cfg = $config;
    }

    /**
     * Write an entry to cache
     *
     * @param string $key   The cache key
     * @param mixed $val    The value we want to store
     */
    public function set($key, $val)
    {
        $tmp = $this->getFilename($key) . '.' . uniqid('', true);
        file_put_contents(
            $tmp,
            sprintf(
                $this->contentSchema,
                time() + $this->cfg->ttl,
                var_export($val, true)
            ),
            LOCK_EX
        );
        rename($tmp, $this->getFilename($key));
    }

    /**
     * Retrieve an entry from cache
     *
     * By including the cache file we are "triggering" OPcache. The next time we are including
     * this file its contents will be served from OPcache instead of a real file-read.
     *
     * @param string        $key        The cache key
     * @param mixed|null    $default    Default return value if item is not available
     * @return mixed
     */
    public function get($key, $default = null)
    {
        /** @noinspection PhpIncludeInspection */
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        @include $this->getFilename($key);

        if (!isset($ttl) || !isset($val)) {
            return $default;
        }

        if ($ttl < time()) {
            $this->remove($key);
            return $default;
        }

        /** @noinspection PhpUndefinedVariableInspection */
        return $val;
    }

    /**
     * Removes cache file for given key if available
     *
     * @param string $key   The cache key
     */
    public function remove($key)
    {
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        @unlink($this->getFilename($key));
    }

    /**
     * Return absoulte path to cache file for given key
     *
     * @param string $key   The cache key
     * @return string
     */
    protected function getFilename($key)
    {
        return sprintf($this->nameSchema, $this->cfg->cachedir, $this->cfg->prefix, $key);
    }
}
