<?php

namespace Opcacheu;

/**
 * Class Config
 * @package Opcacheu
 */
class Config
{
    /**
     * Default TTL
     */
    const TTL = 3600;

    /**
     * Default Prefix
     */
    const PREFIX = 'default';

    /**
     * Default autocreate
     */
    const AUTO = true;

    /**
     * Default autocreate
     */
    const DEFAULT_DIR = 'opcacheu';

    /**
     * @var string Cache files directory
     */
    public $cachedir;

    /**
     * @var int Time To Live
     */
    public $ttl = self::TTL;

    /**
     * @var string Default cache file prefix
     */
    public $prefix = self::PREFIX;

    /**
     * @var bool Autocreate cache directory
     */
    public $autocreate = self::AUTO;

    /**
     * Config constructor.
     *
     * @param array $props      Configuration settings
     */
    public function __construct($props = [])
    {
        $this->cachedir = sys_get_temp_dir() . '/' . self::DEFAULT_DIR;
        foreach ($props as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
        }
    }
}
