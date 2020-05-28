<?php

namespace Gidato\ContainerExtensions\Laminas;

use Laminas\ServiceManager\AbstractPluginManager as LaminasAbstractPluginManager;
use Gidato\Container\ContainerInterface;

trait CacheBuiltTrait
{
    private $cachedBuiltObjects = [];

    /**
     * creates a new one if not created,
     * but, if already created, and should be SINGLETON (ie shared), the returns same one
     */
    public function getWith($name, array $options = null)
    {

        if (!$this->has($name) && $this->hasParent()) {
            return $this->getParent()->getWith($name, $options);
        }

        // convert to string;
        $optionsKey = $this->getOptionKey($options);

        if (isset($this->cachedBuiltObjects[$name][$optionsKey])) {
            return $this->cachedBuiltObjects[$name][$optionsKey];
        }

        $object = $this->createWith($name, $options);

        if (($this->sharedByDefault && ! isset($this->shared[$name]))
            || (isset($this->shared[$name]) && $this->shared[$name])) {
                $this->cachedBuiltObjects[$name][$optionsKey] = $object;
        }

        return $object;
    }

    /**
     * alias for build
     */
    public function createWith($name, array $options = null)
    {
        if (!$this->has($name) && $this->hasParent()) {
            return $this->getParent()->createWith($name, $options);
        }

        $instance = $this->build($name, $options);
        $this->validate($instance);
        return $instance;
    }

    /**
     * alias for build / createWith (but with no options set)
     */
    public function create($name)
    {
        return $this->createWith($name);
    }


    private function getOptionKey(array $options = null) : string
    {
        if ($options === null) {
            return 'null';
        }

        return json_encode($this->convertObjectsToHashes($options));
    }

    private function convertObjectsToHashes(array $options) : array
    {
        return array_map(
            function($option) {
                if (is_object($option)) {
                    return spl_object_hash($option);
                }

                if (is_array($option)) {
                    return $this->convertObjectsToHashes($option);
                }

                return $option;
            },
            $options
        );
    }

}
