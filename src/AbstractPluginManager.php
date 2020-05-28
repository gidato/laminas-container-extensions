<?php

namespace Gidato\ContainerExtensions\Laminas;

use Laminas\ServiceManager\AbstractPluginManager as LaminasAbstractPluginManager;
use Gidato\Container\ContainerInterface;

class AbstractPluginManager extends LaminasAbstractPluginManager implements ContainerInterface
{
    use CacheBuiltTrait;
    use NestedContainersTrait;
    use GetEverythingTrait;

    public function __construct($configInstanceOrParentLocator = null, array $config = [])
    {
        parent::__construct($configInstanceOrParentLocator, $config);
        $this->setParent($configInstanceOrParentLocator);
        $this->creationContext = $this;
    }

}
