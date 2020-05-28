<?php

namespace Gidato\ContainerExtensions\Laminas;

use Laminas\ServiceManager\AbstractPluginManager as LaminasAbstractPluginManager;
use Gidato\Container\ContainerInterface;
use Gidato\ContainerExtensions\Laminas\ServiceManager;
use Gidato\Container\Exception\ServiceNotFoundException;

trait NestedContainersTrait
{
    private $containerPrefix = 'container:';
    private $parent;

    public function get($name, ?array $options = NULL)
    {
        if (!$this->has($name) && $this->hasParent()) {
            return $this->getParent()->get($name, $options);
        }

        return parent::get($name, $options);
    }

    /**
     * Find a container with name specified (to separate conatainer by type)
     */
    public function container(string $name): ContainerInterface
    {
        if (!$this->has($this->containerPrefix . $name)) {
            $this->setService($this->containerPrefix . $name, new NestedContainer($this));
        }

        return parent::get($this->containerPrefix . $name);
    }

    /**
     * allow containers to be valid, otherwise ask parent validateor to check
     */
    public function validate($instance)
    {
        if (empty($this->instanceOf) || $instance instanceof AbstractPluginManager) {
            return;
        }

        parent::validate($instance);
    }

    protected function setParent($parent) : void
    {
        $this->parent = $parent;
    }

    /**
     * check to see if this has a parent
     */
    public function hasParent() : bool
    {
        return !empty($this->parent);
    }

    /**
     * get parent
     */
    public function getParent() // AbstractPluginManager or ServiceManager
    {
        return $this->parent;
    }

}
