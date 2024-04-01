<?php

namespace Boot;

use Boot\Interfaces\ContainerExceptionInterface;
use Boot\Interfaces\ContainerInterface;
use Boot\Src\Abstracts\Singleton;
use Boot\Src\Exceptions\ContainerException;
use Boot\Src\Exceptions\ImplementationException;
use Boot\Src\Exceptions\ImplementationNotFoundException;
use Boot\Src\Exceptions\NotFoundException;
use Boot\Src\Exceptions\UnresolvableInstanceGivenException;
use Boot\Src\Exceptions\UnresolvableParameterGivenException;
use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use TypeError;

class Container implements ContainerInterface
{
    /** @var self */
    protected static self $instance;

    /** @var object[] */
    protected array $instances = [];

    /** @var array */
    protected array $bindings = [];

    /** @var array */
    protected array $implementations = [];

    /** @var string[] */
    protected array $buildStack = [];

    public function __construct()
    {
        self::$instance = $this;
    }

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
     * @param string $abstract
     * @param array $parameters
     * @return mixed
     * @throws ContainerExceptionInterface
     */
    public function make(string $abstract, array $parameters): object
    {
        try {
            return $this->resolve($abstract, $parameters);
        } catch (UnresolvableInstanceGivenException $e) {
            throw new ContainerException($e);
        }
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
     * Describe how to resolve parameter while creating concrete
     *
     * @param string|array $concrete
     * @return ParameterResolveBinder
     */
    public function when(string|array $concrete): ParameterResolveBinder
    {
        return new ParameterResolveBinder($concrete, $this);
    }

    /**
     * Add parameter implementation so the container can resolve it correctly
     *
     * @param string $concrete
     * @param string $needs
     * @param mixed $implementation
     * @return void
     */
    public function addBuildImplementation(string $concrete, string $needs, mixed $implementation): void
    {
        $this->implementations[$concrete][$needs] = $implementation;
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            return self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get created instance for given abstract or try to create one
     *
     * @param string $abstract
     * @param array $parameters
     * @return object
     * @throws UnresolvableInstanceGivenException
     */
    protected function resolve(string $abstract, array $parameters = []): object
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
            $reflection = new ReflectionClass(
                $concrete = $this->getConcrete($abstract)
            );

            if ($reflection->isSubclassOf(Singleton::class)) {
                return $this->createSingleton($reflection, $parameters);
            }

            if (!$reflection->isInstantiable()) {
                throw new UnresolvableInstanceGivenException('Given abstract is not instantiable: ' . $abstract);
            }

            $this->buildStack[] = $concrete;

            if (!empty($constructor = $reflection->getConstructor())) {
                $parameters = $this->resolveConstructorParameters(
                    $constructor->getParameters(),
                    $parameters,
                );
            }

            array_pop($this->buildStack);

            return $reflection->newInstanceArgs($parameters);
        } catch (ReflectionException|UnresolvableParameterGivenException $e) {
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
     * @throws ReflectionException
     * @throws UnresolvableInstanceGivenException|UnresolvableParameterGivenException
     * @see Singleton
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
     * @throws UnresolvableParameterGivenException
     */
    protected function resolveConstructorParameters(
        array $parameters,
        array $initiatedParameters,
    ): array {
        $result = [];

        foreach ($parameters as $parameter) {
            $result[] = $this->getParameterImplementation($parameter, $initiatedParameters[$parameter->getName()] ?? null);
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

    /**
     * @param ReflectionParameter $parameter
     * @throws UnresolvableParameterGivenException
     */
    private function unresolvableParameter(ReflectionParameter $parameter): void
    {
        throw new UnresolvableParameterGivenException(
            'Can not resolve parameter ' .
            '(' . $parameter->getType() . ')' .
            $parameter->getName()
        );
    }

    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws UnresolvableParameterGivenException
     */
    private function getParameterDefaultValue(ReflectionParameter $parameter): mixed
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->allowsNull()) {
            return null;
        }

        $this->unresolvableParameter($parameter);
    }

    /**
     * @param ReflectionParameter $parameter
     * @param mixed $givenValue
     * @return mixed
     * @throws UnresolvableParameterGivenException
     */
    private function getParameterImplementation(ReflectionParameter $parameter, mixed $givenValue): mixed
    {
        try {
            return $this->resolveImplementation($parameter, $givenValue);
        } catch (ImplementationNotFoundException) {
            return $this->resolveFallbackImplementation($parameter, $givenValue);
        }  catch (ImplementationException) {
            return $this->getParameterDefaultValue($parameter);
        }
    }

    /**
     * @return array
     */
    private function getImplementationsForCurrentBuildingConcrete(): array
    {
        return $this->implementations[end($this->buildStack)] ?? [];
    }

    /**
     * @param ReflectionParameter $parameter
     * @param mixed $givenValue
     * @return mixed
     * @throws ImplementationException|ImplementationNotFoundException
     */
    private function resolveImplementation(
        ReflectionParameter $parameter,
        mixed $givenValue,
    ): mixed {
        $implementationsForCurrentBuildingConcrete = $this->getImplementationsForCurrentBuildingConcrete();
        $implementation = $implementationsForCurrentBuildingConcrete[$this->getConcrete($parameter->getName())] ?? null;

        if (is_null($implementation)) {
            throw new ImplementationNotFoundException('No implementation found for given parameter: ' . $parameter->getName());
        }

        if ($implementation instanceof Closure) {
            try {
                return $implementation($givenValue);
            } catch (TypeError $e) {
                throw new ImplementationException($e);
            }
        }

        return $implementation;
    }

    /**
     * @param ReflectionParameter $parameter
     * @param mixed $givenValue
     * @return mixed
     * @throws UnresolvableParameterGivenException
     */
    private function resolveFallbackImplementation(ReflectionParameter $parameter, mixed $givenValue): mixed
    {
        if ($parameter->getType()?->isBuiltin()) {
            if ($givenValue !== null) {
                return $givenValue;
            }
        } else {
            if ($givenValue !== null && !is_array($givenValue)) {
                return $givenValue;
            }
            try {
                return $this->make(ltrim($parameter->getType(), '?'), $givenValue ?? []);
            } catch (ContainerExceptionInterface) {}
        }

        return $this->getParameterDefaultValue($parameter);
    }
}