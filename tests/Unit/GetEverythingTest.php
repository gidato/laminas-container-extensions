<?php

namespace Tests\Unit;

use Tests\TestCase;
use Gidato\Container\ContainerInterface;
use Gidato\Container\FactoryInterface;
use Gidato\ContainerExtensions\Laminas\NestedContainer;

class GetEverythingTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->container->setService('a', (object) ['a' => 'b']);
        $this->container->setService('b', (object) ['c' => 'd']);
        $this->container->setAlias('c', 'a');

        $sub = new NestedContainer($this->container);
        $sub->setService('d', (object) ['e' => 'f']);

        $this->container->setService('container:sub', $sub);
    }

    /** @test */
    public function get_all_keys_exclude_aliases_and_containers()
    {
        $keys = $this->container->getKeys();
        sort($keys); // no guarantee of order
        $this->assertEquals(['a', 'b'], $keys);
    }

    /** @test */
    public function get_all_keys_include_aliases_exclude_containers()
    {
        $keys = $this->container->getKeys(false, true);
        sort($keys); // no guarantee of order
        $this->assertEquals(['a','b', 'c'], $keys);
    }

    /** @test */
    public function get_all_keys_include_containers_exclude_aliases()
    {
        $keys = $this->container->getKeys(true);
        sort($keys); // no guarantee of order
        $this->assertEquals(['a','b', 'container:sub'], $keys);
    }

    /** @test */
    public function get_all_keys_include_containers_and_aliases()
    {
        $keys = $this->container->getKeys(true, true);
        sort($keys); // no guarantee of order
        $this->assertEquals(['a', 'b', 'c' ,'container:sub'], $keys);
    }

    /** @test */
    public function get_all_exclude_containers()
    {
        $all = $this->container->getAll();
        ksort($all); // no guarantee of order
        $this->assertSame($this->container->get('a'), $all['a']);
        $this->assertSame($this->container->get('b'), $all['b']);
        $this->assertCount(2, $all);
    }

    /** @test */
    public function get_all_include_containers()
    {
        $all = $this->container->getAll(true);
        ksort($all); // no guarantee of order
        $this->assertSame($this->container->get('a'), $all['a']);
        $this->assertSame($this->container->get('b'), $all['b']);
        $this->assertSame($this->container->container('sub'), $all['container:sub']);
        $this->assertCount(3, $all);
    }

}
