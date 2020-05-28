<?php

namespace Gidato\ContainerExtensions\Laminas;

use Laminas\ServiceManager\ServiceManager as LaminasServiceManager;
use Gidato\Container\ContainerInterface;

class ServiceManager extends LaminasServiceManager implements ContainerInterface
{
    use CacheBuiltTrait;
    use NestedContainersTrait;
    use GetEverythingTrait;
}
