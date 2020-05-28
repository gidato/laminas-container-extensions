<?php

namespace Gidato\ContainerExtensions\Laminas;

use Laminas\ServiceManager\AbstractPluginManager as LaminasAbstractPluginManager;
use Gidato\Container\ContainerInterface;

trait GetEverythingTrait
{
    /**
     * returns references to everything that has been set up in the container
     */
    public function getKeys(bool $includeContainers = false, bool $includeAliases = false): array
    {
        $keys = array_unique(array_merge(array_keys($this->factories), array_keys($this->services)));
        if ($includeAliases) {
            $keys = array_merge($keys, array_keys($this->aliases));
        }

        if ($includeContainers) {
            return $keys;
        }

        foreach ($keys as $i => $key) {
            if (substr($key, 0, strlen($this->containerPrefix)) == $this->containerPrefix) {
                unset($keys[$i]);
            }
        }

        // reset index and return;
        return array_values($keys);
    }

    /**
     * returns actual built objects for everything
     */
    public function getAll(bool $includeContainers = false): array
    {
        $keys = array_unique(array_merge(array_keys($this->factories), array_keys($this->services)));

        $objects = [];
        foreach( $keys as $key ) {
            if ($includeContainers || substr($key, 0, strlen($this->containerPrefix)) != $this->containerPrefix) {
                $objects[$key] = $this->get($key);
            }
        }

        return $objects;
    }
}
