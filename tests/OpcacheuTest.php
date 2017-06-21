<?php

namespace OpcacheuTest;

use Opcacheu\Config;
use Opcacheu\Opcacheu;
use PHPUnit\Framework\TestCase;

/**
 * Class OpcacheuTest
 * @package OpcacheuTest
 * @SuppressWarnings()
 */
class OpcacheuTest extends TestCase
{
    public function testOpcacheAvailable()
    {
        $this->assertEquals(1, intval(ini_get('opcache.enable')), 'OPcache is disabled by config');
        $this->assertEquals(1, intval(ini_get('opcache.enable_cli')), 'CLI OPcache is disabled by config');
    }

    public function testDefaultConfig()
    {
        $cache = new Opcacheu(new Config());

        $cache->set('testvar', 'testval1');
        $this->assertEquals('testval1', $cache->get('testvar'), 'Cache invalid');

        $cache->set('testvar', 'testval2');
        $this->assertEquals('testval2', $cache->get('testvar'), 'Cache invalid (updated)');

        $cache->remove('testvar');
        $this->assertNull($cache->get('testvar'), 'Could not remove from cache');
    }
}
