<?php

namespace OpcacheuTest;

use Opcacheu\Config;
use Opcacheu\Opcacheu;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 * @package OpcacheuTest
 * @covers \Opcacheu\Config
 */
class ConfigTest extends TestCase
{
    public function testDefaultConfig()
    {
        $config = new Config();
        $this->assertStringStartsWith(sys_get_temp_dir(), $config->cachedir, 'Default cachedir is not valid');
        $this->assertEquals(3600, $config->ttl, 'Default ttl is not valid');
        $this->assertEquals('default', $config->prefix, 'Default prefix is not valid');

        if (is_dir($config->cachedir)) {
            rmdir($config->cachedir);
        }
    }

    public function testCustomConfig()
    {
        $data = $this->getConfig();
        $config = new Config($data);

        $this->assertFalse(is_dir($config->cachedir), 'Custom cachedir exists');
        $this->assertEquals($data['ttl'], $config->ttl, 'Custom ttl is not valid');
        $this->assertEquals($data['prefix'], $config->prefix, 'Custom prefix is not valid');

        if (is_dir($config->cachedir)) {
            rmdir($config->cachedir);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testAutocreate()
    {
        $data = $this->getConfig();
        $config = new Config($data);
        /** @noinspection PhpUnusedLocalVariableInspection */
        $cache = new Opcacheu($config);

        $this->assertTrue(is_dir($config->cachedir), 'Custom cachedir is not a directory');
        $this->assertTrue(is_writeable($config->cachedir), 'Custom cachedir is not writeable');
        $this->assertEquals($data['ttl'], $config->ttl, 'Custom ttl is not valid');
        $this->assertEquals($data['prefix'], $config->prefix, 'Custom prefix is not valid');

        if (is_dir($config->cachedir)) {
            rmdir($config->cachedir);
        }
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return [
            'cachedir' => realpath(__DIR__ . '/..') . '/autocreate-test-dir',
            'ttl' => 300,
            'prefix' => 'test',
            'autocreate' => true,
        ];
    }
}
