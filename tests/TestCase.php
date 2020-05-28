<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Gidato\ContainerExtensions\Laminas\ServiceManager;

abstract class TestCase extends BaseTestCase
{
    protected $container;

    public function setUp(): void
    {
        $this->container = new ServiceManager();
    }
}
