<?php


namespace SAREhub\Microt\App;


use SAREhub\Microt\App\Middleware\MiddlewareInjector;

class AppRunOptions
{
    /**
     * @var ContainerConfigurator
     */
    private $containerConfigurator;

    /**
     * @var MiddlewareInjector
     */
    private $middlewareInjector;

    public function __construct(ContainerConfigurator $containerConfigurator, MiddlewareInjector $middlewareInjector)
    {
        $this->containerConfigurator = $containerConfigurator;
        $this->middlewareInjector = $middlewareInjector;
    }

    public static function createWithCompiledContainer(ContainerConfigurator $containerConfigurator, MiddlewareInjector $middlewareInjector): self
    {
        $compiledContainerConfigurator = CompiledContainerConfigurator::create($containerConfigurator);
        return new self($compiledContainerConfigurator, $middlewareInjector);
    }

    public function getContainerConfigurator(): ContainerConfigurator
    {
        return $this->containerConfigurator;
    }

    public function getMiddlewareInjector(): MiddlewareInjector
    {
        return $this->middlewareInjector;
    }
}