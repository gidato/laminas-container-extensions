<?php

namespace Tests\Unit;

use Tests\TestCase;
use Gidato\Container\ContainerInterface;
use Gidato\Container\FactoryInterface;
use Gidato\ContainerExtensions\Laminas\NestedContainer;

class NestedContainerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->container->setService('container:sub', new NestedContainer($this->container));
    }

    /** @test */
    public function get_container_that_exists()
    {
        $sub = $this->container->container('sub');
        $this->assertInstanceOf(NestedContainer::class, $sub);
    }

    /** @test */
    public function get_container_that_does_not_exist()
    {
        $sub = $this->container->container('new');
        $this->assertInstanceOf(NestedContainer::class, $sub);
    }

    /** @test */
    public function get_object_when_in_nested_container()
    {
        $a = (object) ['a' => 'b'];
        $b = (object) ['b' => 'c'];
        $this->container->container('sub')->setService('a', $a);
        $this->container->setService('a', $b);
        $this->assertSame($a, $this->container->container('sub')->get('a'));
    }

    /** @test */
    public function get_object_when_in_parent_of_nested_container()
    {
        $b = (object) ['b' => 'c'];
        $this->container->setService('a', $b);
        $this->assertSame($b, $this->container->container('sub')->get('a'));
    }

    /** @test */
    public function get_object_with_options_when_in_nested_container()
    {
        $b = (object) ['b' => 'c'];
        $this->container->container('sub')->setFactory('a', NestedTestFactory::class);
        $this->container->setService('a', $b);
        $a1 = $this->container->container('sub')->getWith('a',['x'=>'y']);
        $a2 = $this->container->container('sub')->getWith('a',['x'=>'y']);
        $a3 = $this->container->container('sub')->getWith('a',['x'=>'z']);

        $this->assertSame($a1, $a2);
        $this->assertNotSame($a1, $a3);
        $this->assertEquals('y', $a1->x);
    }

    /** @test */
    public function get_object_with_options_when_in_parent_of_nested_container()
    {
        $this->container->setFactory('a', NestedTestFactory::class);
        $a1 = $this->container->container('sub')->getWith('a',['x'=>'y']);
        $a2 = $this->container->container('sub')->getWith('a',['x'=>'y']);
        $a3 = $this->container->container('sub')->getWith('a',['x'=>'z']);

        $this->assertSame($a1, $a2);
        $this->assertNotSame($a1, $a3);
        $this->assertEquals('y', $a1->x);
    }

    /** @test */
    public function get_parent_when_has_parent()
    {
        $this->assertSame($this->container, $this->container->container('sub')->getParent());
    }

    /** @test */
    public function get_parent_when_is_top_level()
    {
        $this->assertEquals(null, $this->container->getParent());
    }

    /** @test */
    public function check_has_parent_when_has_parent()
    {
        $this->assertTrue($this->container->container('sub')->hasParent());
    }

    /** @test */
    public function check_has_parent_when_is_top_level()
    {
        $this->assertFalse($this->container->hasParent());
    }

    /** @test */
    public function check_has_object_when_in_sub_container()
    {
        $this->container->container('sub')->setFactory('a', NestedTestFactory::class);
        $this->container->container('sub')->setService('b', (object) ['a' => 'b']);
        $this->assertTrue($this->container->container('sub')->has('a'));
        $this->assertTrue($this->container->container('sub')->has('b'));
    }

    /** @test */
    public function check_does_not_have_object_when_in_parent()
    {
        $this->container->setService('c', (object) ['c' => 'd']);
        $this->assertFalse($this->container->container('sub')->has('c'));
    }

}

class NestedTestFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $parameters = null)
    {
        return (object) $parameters;
    }
}
