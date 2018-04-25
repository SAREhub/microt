<?php


namespace SAREhub\Microt\App\Controller;

use SAREhub\Microt\App\Middleware\MiddlewareInjector;
use Slim\App;
use Slim\Interfaces\RouteGroupInterface;

class ControllerActionRoutes implements MiddlewareInjector, \JsonSerializable
{

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var string
     */
    private $controllerClass;

    /**
     * @var ControllerActionRoute[]
     */
    private $routes = [];

    /**
     * @var callable[]
     */
    private $middlewares = [];

    public function __construct(string $baseUri, string $controllerClass)
    {
        $this->baseUri = $baseUri;
        $this->controllerClass = $controllerClass;
    }

    public static function create(string $baseUri, string $controllerClass): ControllerActionRoutes
    {
        return new self($baseUri, $controllerClass);
    }

    public function post(string $pattern, string $action): ControllerActionRoute
    {
        $route = ControllerActionRoute::post($pattern, $action);
        $this->addRoute($route);
        return $route;
    }

    public function get(string $pattern, string $action): ControllerActionRoute
    {
        $route = ControllerActionRoute::get($pattern, $action);
        $this->addRoute($route);
        return $route;
    }

    public function put(string $pattern, string $action): ControllerActionRoute
    {
        $route = ControllerActionRoute::put($pattern, $action);
        $this->addRoute($route);
        return $route;
    }

    public function patch(string $pattern, string $action): ControllerActionRoute
    {
        $route = ControllerActionRoute::patch($pattern, $action);
        $this->addRoute($route);
        return $route;
    }

    public function delete(string $pattern, string $action): ControllerActionRoute
    {
        $route = ControllerActionRoute::delete($pattern, $action);
        $this->addRoute($route);
        return $route;
    }

    public function addRoute(ControllerActionRoute $r): self
    {
        $r->controller($this->getController());
        $r->pattern($this->getBaseUri() . $r->getPattern());
        $this->routes[] = $r;
        return $this;
    }

    public function addMiddleware(callable $middleware): ControllerActionRoutes
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function injectTo(App $app)
    {
        $routeGroup = $this->injectRoutes($app);
        $this->injectMiddlewares($routeGroup);
    }

    private function injectRoutes(App $app): RouteGroupInterface
    {
        $routes = $this->getRoutes();
        $routeGroup = $app->group($this->baseUri, function () use ($routes) {
            foreach ($routes as $route) {
                /** @var App $this */
                $route->injectTo($this);
            }
        });
        return $routeGroup;
    }

    private function injectMiddlewares(RouteGroupInterface $routeGroup)
    {
        foreach ($this->getMiddlewares() as $middleware) {
            $routeGroup->add($middleware);
        }
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getController(): string
    {
        return $this->controllerClass;
    }

    /**
     * @return ControllerActionRoute[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return callable[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function jsonSerialize()
    {
        return [
            "baseUri" => $this->getBaseUri(),
            "controller" => $this->getController(),
            "routes" => $this->getRoutes(),
            "middlewares" => $this->jsonSerializeMiddlewares()
        ];
    }

    private function jsonSerializeMiddlewares(): array
    {
        $json = [];
        foreach ($this->getMiddlewares() as $middleware) {
            $json[] = $middleware instanceof \JsonSerializable ? $middleware : get_class($middleware);
        }
    }
}