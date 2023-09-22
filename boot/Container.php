<?php

namespace Boot;

use Boot\Interfaces\ContainerExceptionInterface;
use Boot\Interfaces\ContainerInterface;
use Boot\Src\Abstracts\Entity;
use Boot\Src\Abstracts\Singleton;
use Boot\Src\Abstracts\UpdateUnit;
use Boot\Src\Exceptions\ContainerException;
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
    protected array $primitiveBuildImplementations = [];

    /** @var bool */
    protected bool $resolvingTelegramEntity = false;

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
    public function make(string $abstract, array $parameters): mixed
    {
        try {
            $this->resolvingTelegramEntity = is_subclass_of($abstract, Entity::class) ||
                is_subclass_of($abstract, UpdateUnit::class);

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
        $this->primitiveBuildImplementations[$concrete][$needs] = $implementation;
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
            $this->resolvingTelegramEntity ?
                $parameterName = camel_case_to_snake_case($parameter->getName()) :
                $parameterName = $parameter->getName();

            $result[] = $this->getParameterImplementation($parameter, $initiatedParameters[$parameterName] ?? null);
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
     * @param mixed|null $givenValue
     * @return mixed|null
     * @throws UnresolvableParameterGivenException
     */
    private function resolvePrimitive(ReflectionParameter $parameter, mixed $givenValue = null): mixed
    {
        if ($implemented = $this->getParameterImplementation($parameter, $givenValue)) {
            return $implemented;
        }

        if ($givenValue !== null) {
            return $givenValue;
        }

        return $this->getParameterDefaultValue($parameter);
    }

    /**
     * @param ReflectionParameter $parameter
     * @param mixed $givenValue
     * @return mixed
     * @throws UnresolvableParameterGivenException
     */
    private function resolveClass(
        ReflectionParameter $parameter,
        mixed $givenValue,
    ): mixed {
        try {
            if ($implemented = $this->getParameterImplementation($parameter, $givenValue)) {
                return $implemented;
            }

            if (is_array($givenValue) || $givenValue === null) {
                return $this->resolve(
                    ltrim($parameter->getType(), '?'),
                    $givenValue ?? [],
                );
            }

            return $givenValue;
        } catch (UnresolvableInstanceGivenException) {
            array_pop($this->buildStack);
            return $this->getParameterDefaultValue($parameter);
        }
    }

    /**
     * @param ReflectionParameter $parameter
     * @param mixed $givenValue
     * @return mixed
     * @throws UnresolvableParameterGivenException
     */
    private function getParameterImplementation(ReflectionParameter $parameter, mixed $givenValue): mixed
    {
        $implementationsForCurrentBuildingConcrete = $this->primitiveBuildImplementations[
            end($this->buildStack)
        ] ?? [];

        $implementation = $implementationsForCurrentBuildingConcrete[
            $this->getConcrete($parameter->getName())
        ] ?? null;

        if ($implementation instanceof Closure) {
            try {
                return $implementation($givenValue);
            } catch (TypeError) {
                return $this->getParameterDefaultValue($parameter);
            }
        }

        if (!is_null($implementation)) {
            return $implementation;
        }

        if (is_array($givenValue) || is_null($givenValue)) {
            try {
                return $this->resolve(
                    ltrim($parameter->getType(), '?'),
                    $givenValue ?? []
                );
            } catch (UnresolvableInstanceGivenException) {}
        }

        if ($givenValue !== null) {
            return $givenValue;
        }

        return $this->getParameterDefaultValue($parameter);
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
     * @return void
     */
    private function flush(): void
    {
        $this->buildStack = [];
        $this->resolvingTelegramEntity = false;
    }
}