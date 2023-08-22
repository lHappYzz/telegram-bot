<?php

namespace Boot;

use Boot\Interfaces\ContainerInterface;
use Boot\Src\Abstracts\Singleton;
use Boot\Src\Exceptions\ContainerException;
use Boot\Src\Exceptions\NotFoundException;
use Boot\Src\Exceptions\UnresolvableInstanceGivenException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerInterface
{
    /** @var object[] */
    protected array $instances = [];

    /** @var array */
    protected array $bindings = [];

    /**
     * @inheritDoc
     */
    public function get(string $id): mixed
    {
        try {
            return $this->resolve($id);
        } catch (UnresolvableInstanceGivenException $exception) {
            if ($this->has($id)) {
                throw new ContainerException($exception);
            }

            throw new NotFoundException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return isset($this->instances[$id]) || isset($this->bindings[$id]);
    }

    /**
     * Bind interface with implementation
     *
     * @param string $abstract
     * @param string $concrete
     * @param bool $singleton
     * @return void
     */
    public function bind(string $abstract, string $concrete, bool $singleton = false): void
    {
        $this->bindings[$abstract] = compact('concrete', 'singleton');
    }

    /**
     * Bind instance using "singleton" method if you want only one instance of given "concrete"
     *
     * @param string $abstract
     * @param string|object|null $concrete
     * @return void
     */
    public function singleton(string $abstract, null|string|object $concrete = null): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        if (gettype($concrete) === 'object') {
            $this->instances[$abstract] = $concrete;
        } else {
            $this->bind($abstract, $concrete, true);
        }
    }

    /**
     * Get created instance for given abstract or try to create one
     *
     * @param string $abstract
     * @param array $parameters
     * @return object
     * @throws UnresolvableInstanceGivenException
     */
    protected function resolve(string $abstract, array $parameters = []): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $instance = $this->create($abstract, $parameters);

        if ($this->isSingleton($abstract)) {
            return $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    /**
     * Trying to create concrete for given abstract
     *
     * @param string $abstract
     * @param array $parameters
     * @return object
     * @throws UnresolvableInstanceGivenException
     */
    protected function create(string $abstract, array $parameters): object
    {
        try {
            $reflection = new ReflectionClass($this->getConcrete($abstract));

            if ($reflection->isSubclassOf(Singleton::class)) {
                return $this->createSingleton($reflection, $parameters);
            }

            if (!$reflection->isInstantiable()) {
                throw new UnresolvableInstanceGivenException('Given abstract is not instantiable: ' . $abstract);
            }

            if (!empty($constructor = $reflection->getConstructor())) {
                $parameters = $this->resolveConstructorParameters($constructor->getParameters(), $parameters);
            }

            return $reflection->newInstanceArgs($parameters);
        } catch (ReflectionException $e) {
            throw new UnresolvableInstanceGivenException($e);
        }
    }

    /**
     * Retrieve concrete for given abstract
     *
     * @param string $abstract
     * @return string
     */
    protected function getConcrete(string $abstract): string
    {
        if (isset($this->bindings[$abstract]['concrete'])) {
            return $this->bindings[$abstract]['concrete'];
        }

        return $this->bindings[$abstract] ?? $abstract;
    }

    /**
     * Creates instance for Singleton classes
     *
     * @param ReflectionClass $reflection
     * @param array $parameters
     * @return object
     * @see Singleton
     * @throws ReflectionException
     * @throws UnresolvableInstanceGivenException
     * @todo Remove when the Singleton pattern will be removed
     */
    protected function createSingleton(ReflectionClass $reflection, array $parameters): object
    {
        if ($reflection->isAbstract()) {
            throw new UnresolvableInstanceGivenException();
        }

        $instance = $reflection->newInstanceWithoutConstructor();
        $singleton = new ReflectionClass(Singleton::class);

        $singletonInitiatedInstances = $singleton->getProperty('aoInstance');
        $singletonInitiatedInstances->setAccessible(true);
        $singletonInitiatedInstances->setValue(array_merge($singletonInitiatedInstances->getValue(), [$instance::class => $instance]));

        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);

        $constructor->invoke(
            $instance,
            ...$this->resolveConstructorParameters($constructor->getParameters(), $parameters)
        );

        return $instance;
    }

    /**
     * Resolves constructor parameters if possible
     *
     * @param ReflectionParameter[] $parameters
     * @param array $initiatedParameters
     * @return array
     * @throws UnresolvableInstanceGivenException
     */
    protected function resolveConstructorParameters(array $parameters, array $initiatedParameters): array
    {
        $result = [];

        foreach ($parameters as $parameter) {
            if (in_array($parameter->getName(), $initiatedParameters)) {
                $result[] = $initiatedParameters[$parameter->getName()];
                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $result[] = $parameter->getDefaultValue();
                continue;
            }

            if (!$parameter->getType() || $parameter->getType()->isBuiltin()) {
                throw new UnresolvableInstanceGivenException(
                    'Can not resolve parameter (' .
                    $parameter->getType() . ')' .
                    $parameter->getName()
                );
            }

            $result[] = $this->resolve($parameter->getType(), []);
        }

        return $result;
    }

    /**
     * Check if "abstract" should be created only once
     *
     * @param string $abstract
     * @return bool
     */
    private function isSingleton(string $abstract): bool
    {
        if (isset($this->instances[$abstract])) {
            return true;
        }

        return $this->bindings[$abstract]['singleton'] ?? false;
    }
}