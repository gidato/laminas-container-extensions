<?php

namespace Tests\Unit;

use Tests\TestCase;
use Gidato\Container\ContainerInterface;
use Gidato\Container\FactoryInterface;

class CacheTest extends TestCase
{
    /** @test */
    public function get_with_option_returns_same_instance()
    {
        $this->container->setFactory('test', CacheTestFactory::class);
        $a1 = $this->container->getWith('test',['a' => 'b']);
        $b1 = $this->container->getWith('test',['b' => 'c']);
        $a2 = $this->container->getWith('test',['a' => 'b']);

        $this->assertSame($a1, $a2);
        $this->assertNotSame($a1, $b1);
    }

    /** @test */
    public function create_with_option_returns_new_instance()
    {
        $this->container->setFactory('test', CacheTestFactory::class);
        $a1 = $this->container->getWith('test',['a' => 'b']);
        $a2 = $this->container->getWith('test',['a' => 'b']);
        $a3 = $this->container->createWith('test',['a' => 'b']);

        $this->assertSame($a1, $a2);
        $this->assertNotSame($a1, $a3);
    }

    /** @test */
    public function create_without_option_returns_new_instance()
    {
        $this->container->setFactory('test', CacheTestFactory::class);
        $a1 = $this->container->get('test');
        $a2 = $this->container->get('test');
        $a3 = $this->container->create('test');

        $this->assertSame($a1, $a2);
        $this->assertNotSame($a1, $a3);
    }

    /** @test */
    public function get_with_option_returns_new_instance_when_not_shared()
    {
        $this->container->setFactory('test', CacheTestFactory::class);
        $this->container->setShared('test', false);

        $a1 = $this->container->getWith('test',['a' => 'b']);
        $a2 = $this->container->getWith('test',['a' => 'b']);

        $this->assertNotSame($a1, $a2);
    }

    /** @test */
    public function get_with_option_returns_new_instance_when_not_shared_by_default()
    {
        $container = new \Gidato\ContainerExtensions\Laminas\ServiceManager(['shared_by_default' => false]);

        $container->setFactory('test', CacheTestFactory::class);

        $a1 = $container->getWith('test',['a' => 'b']);
        $a2 = $container->getWith('test',['a' => 'b']);

        $this->assertNotSame($a1, $a2);
    }

}

class CacheTestFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $parameters = null)
    {
        return (object) $parameters;
    }
}
