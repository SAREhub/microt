<?php


namespace SAREhub\Microt\App\Controller;


use SAREhub\Commons\Misc\InvokableProvider;

abstract class ControllerRoutesProvider extends InvokableProvider
{
    /**
     * @var callable[]
     */
    private $groupMiddlewares;

    /**
     * @var callable[]
     */
    private $actionMiddlewares;

    public function __construct(array $groupMiddlewares = [], array $actionMiddlewares = [])
    {
        $this->groupMiddlewares = $groupMiddlewares;
        $this->actionMiddlewares = $actionMiddlewares;
    }

    /**
     * @return ControllerActionRoutes
     */
    public function get()
    {
        $routes = ControllerActionRoutes::create($this->getBaseUri(), $this->getControllerClass());
        return $this->inject($routes);
    }

    protected function inject(ControllerActionRoutes $routes): ControllerActionRoutes
    {
        $this->injectRoutes($routes);
        $this->injectGroupMiddlewares($routes);
        $this->injectActionsMiddlewares($routes);
        return $routes;
    }

    protected abstract function getBaseUri(): string;

    protected abstract function getControllerClass(): string;

    protected abstract function injectRoutes(ControllerActionRoutes $routes);

    protected function injectGroupMiddlewares(ControllerActionRoutes $routes)
    {
        foreach ($this->groupMiddlewares as $middleware) {
            $routes->addMiddleware($middleware);
        }
    }

    protected function injectActionsMiddlewares(ControllerActionRoutes $routes)
    {
        foreach ($routes->getRoutes() as $route) {
            $this->injectActionMiddlewares($route);
        }
    }

    protected function injectActionMiddlewares(ControllerActionRoute $route)
    {
        $action = $route->getAction();
        if (isset($this->actionMiddlewares[$action])) {
            foreach ($this->actionMiddlewares[$action] as $middleware) {
                $route->addMiddleware($middleware);
            }
        }
    }
}
