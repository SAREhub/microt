<?php


namespace SAREhub\Microt\App\Controller;


use SAREhub\Commons\Misc\InvokableProvider;

abstract class ControllerRoutesProvider extends InvokableProvider
{
    /**
     * @var callable[]
     */
    private $middlewares;

    public function __construct(array $middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    /**
     * @return ControllerActionRoutes
     */
    public function get()
    {
        $routes = ControllerActionRoutes::create($this->getBaseUri(), $this->getControllerClass());
        $this->injectMiddlewares($routes);
        $this->injectRoutes($routes);
        return $routes;
    }

    protected abstract function getBaseUri(): string;

    protected abstract function getControllerClass(): string;

    private function injectMiddlewares(ControllerActionRoutes $routes)
    {
        foreach ($this->middlewares as $middleware) {
            $routes->addMiddleware($middleware);
        }
    }

    protected abstract function injectRoutes(ControllerActionRoutes $routes);
}