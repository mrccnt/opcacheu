<?php

namespace Opcacheu;

/**
 * Class Config
 * @package Opcacheu
 */
class Config
{
    /**
     * @var string  Cache files directory
     */
    public $cachedir;

    /**
     * @var int Time To Live
     */
    public $ttl = 3600;

    /**
     * @var string  Default cache file prefix
     */
    public $prefix = 'default';

    /**
     * Config constructor.
     *
     * @param array $props          Configuration settings
     * @param bool  $autocreate     Automatically check & create cache directory if it does not exist
     * @throws \Exception
     */
    public function __construct($props = [], $autocreate = false)
    {
        $this->cachedir = sys_get_temp_dir() . '/opcacheu';
        foreach ($props as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
        }
        if ($autocreate) {
            if (!is_dir($this->cachedir)) {
                if (!mkdir($this->cachedir, 0755, true)) {
                    throw new \Exception('Could not create cache dir', 1);
                }
            }
            if (!is_writeable($this->cachedir)) {
                throw new \Exception('Cache dir is not writeable', 1);
            }
        }
    }
}
