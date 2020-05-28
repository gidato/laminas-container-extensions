# Gidato / Laminas-Container-Extensions

```

composer require gidato/laminas-container-extensions

```

You then need to find the following line in \\config\\container.php

```
use Laminas\ServiceManager\ServiceManager;
```
And change it as follows:

```
use Gidato\ContainerExtensions\Laminas;
```

#### Nested Containers

A separate container can be created and used within a container, effectively namespacing the bound elements.

This allows specific parts of the application to be directly associated with a specific container.

To access sub containers, you can use `$container->container("name-of-container")`, and then bind things to this level.

#### Cached Objects Which Use Options To Build

Rather than using `$container->get('name')`, you can use `$container->getWith('name', ['options'])`.  This will create the object using the options passed, but will return exactly the same instance on subsequent request with the same options providing the object is defined as shared, or the default is shared.

#### Getting New Instances

Rather than using `build()`, you should use `create()` and `createWith()` to allow the package to be used with other container implementations.

#### Getting Everything From A Container

You can get all keys within a container, or all instances.

```
public function getKeys(bool $includeContainer = false, bool $includeAliases = false);

$container->getKeys(); // returns all keys, but excludes containers and aliases
$container->getKeys(true); // returns all keys including containers, but excludes aliases
$container->getKeys(false, true); // returns all keys including aliases but excludes containers
$container->getKeys(true, true); // returns all keys including containers and aliases
```

Or get all instances (index by the key).  These will be built if not already.
```
public function getAll(bool $includeContainer = false);

$container->getAll(); // returns all instances, but excludes containers
$container->getAll(true); // returns all instances, and includes containers
```

## License

This software is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
