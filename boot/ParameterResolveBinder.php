<?php

namespace Boot;

class ParameterResolveBinder
{
    /** @var string */
    protected string $needs;

    /**
     * @param array|string $concrete
     * @param Container $container
     */
    public function __construct(protected array|string $concrete, protected Container $container) {}

    /**
     * Provide parameter name of given concrete to be resolved in container
     *
     * @param string $variableName
     * @return $this
     */
    public function needs(string $variableName): self
    {
        $this->needs = $variableName;

        return $this;
    }

    /**
     * Provide parameter implementation
     *
     * @param mixed $implementation
     * @return void
     */
    public function give(mixed $implementation): void
    {
        foreach ($this->concrete as $concrete) {
            $this->container->addBuildImplementation($concrete, $this->needs, $implementation);
        }
    }
}