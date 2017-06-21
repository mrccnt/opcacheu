<?php

namespace Opcacheu;

/**
 * Class Opcacheu
 * @package Opcacheu
 */
class Opcacheu
{
    /**
     * Content schema for cache files in $cachedir
     */
    const CONTENT_FORMAT = '<?php $ttl = %s; $val = %s;';

    /**
     * Path schema for cache files in $cachedir
     */
    const FILE_FORMAT = '%s/%s_%s.php';

    /**
     * @var Config  Cache configuration
     */
    protected $cfg;

    /**
     * Construct by passing in a config object
     *
     * @param Config $config Cache configuration
     * @throws \Opcacheu\OpcacheuException
     */
    public function __construct($config)
    {
        $this->cfg = $config;

        if (!is_dir($this->cfg->cachedir)) {
            if (!$this->cfg->autocreate) {
                throw new OpcacheuException(__FUNCTION__ . '(): Cache dir does not exist');
            }
            /** @noinspection PhpUsageOfSilenceOperatorInspection */
            if (!@mkdir($this->cfg->cachedir, 0755, true)) {
                throw new OpcacheuException(__FUNCTION__ . '(): Could not create cache dir');
            }
        }

        if (!is_writeable($this->cfg->cachedir)) {
            throw new OpcacheuException(__FUNCTION__ . '(): Cache dir is not writeable', 1);
        }
    }

    /**
     * Write an entry to cache
     *
     * @param string $key   The cache key
     * @param mixed $val    The value we want to store
     */
    public function set($key, $val)
    {
        $file = $this->getFilename($key);
        $tmp = $file . '.' . uniqid('', true);

        /** @noinspection PhpParamsInspection */
        if (opcache_is_script_cached($file)) {
            if (!opcache_invalidate($file, true)) {
                trigger_error(__FUNCTION__ . '(): Could not invalidate cache', E_USER_WARNING);
            }
        }

        file_put_contents(
            $tmp,
            sprintf(
                self::CONTENT_FORMAT,
                time() + $this->cfg->ttl,
                var_export($val, true)
            ),
            LOCK_EX
        );

        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        if (!@rename($tmp, $file)) {
            trigger_error(__FUNCTION__ . '(): Could not rename file', E_USER_WARNING);
        }

        if (!opcache_compile_file($file)) {
            trigger_error(__FUNCTION__ . '(): Could not compile file', E_USER_WARNING);
        }
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
        $file = $this->getFilename($key);

        try {
            /** @noinspection PhpIncludeInspection */
            include $file;
        } catch (\Exception $exception) {
            return $default;
        }

        if (!isset($ttl) || !isset($val)) {
            return $default;
        }

        if ($ttl < time()) {
            // First get() after ttl is invalid, results in longer response time (removing file)
            $this->remove($key);
            return $default;
        }

        /** @noinspection PhpParamsInspection */
        if (!opcache_is_script_cached($file)) {
            if (!opcache_compile_file($file)) {
                trigger_error(__FUNCTION__ . '(): Could not compile file', E_USER_WARNING);
            }
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
        $file = $this->getFilename($key);

        /** @noinspection PhpParamsInspection */
        if (opcache_is_script_cached($file)) {
            if (!opcache_invalidate($file, true)) {
                trigger_error(__FUNCTION__ . '(): Could not invalidate cache', E_USER_WARNING);
            }
        }

        if (file_exists($file)) {
            /** @noinspection PhpUsageOfSilenceOperatorInspection */
            @unlink($file);
        }
    }

    /**
     * Return absoulte path to cache file for given key
     *
     * @param string $key   The cache key
     * @return string
     */
    protected function getFilename($key)
    {
        return sprintf(self::FILE_FORMAT, $this->cfg->cachedir, $this->cfg->prefix, $key);
    }
}
