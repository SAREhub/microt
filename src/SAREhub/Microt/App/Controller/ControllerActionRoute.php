<?php


namespace SAREhub\Microt\App\Controller;


use SAREhub\Microt\App\Middleware\MiddlewareInjector;
use SAREhub\Microt\App\Route\RouteMiddlewareInjector;
use Slim\App;

class ControllerActionRoute implements MiddlewareInjector, \JsonSerializable
{
    const ACTION_POSTFIX = 'Action';

    /**
     * @var string
     */
    private $controllerClass;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $httpMethod;

    /**
     * @var string
     */
    private $pattern = '';

    /**
     * @var callable[]
     */
    private $middlewares = [];

    public static function post(string $pattern, string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('POST')->pattern($pattern)->action($action);
    }

    public static function get(string $pattern, string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('GET')->pattern($pattern)->action($action);
    }

    public static function put(string $pattern, string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('PUT')->pattern($pattern)->action($action);
    }

    public static function patch(string $pattern, string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('PATCH')->pattern($pattern)->action($action);
    }

    public static function delete(string $pattern, string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('DELETE')->pattern($pattern)->action($action);
    }

    public static function options(string $pattern, string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('OPTIONS')->pattern($pattern)->action($action);
    }

    public static function route(): ControllerActionRoute
    {
        return new self();
    }

    public function controller(string $class): ControllerActionRoute
    {
        $this->controllerClass = $class;
        return $this;
    }

    public function action(string $name): ControllerActionRoute
    {
        $this->action = $name;
        return $this;
    }

    public function httpMethod(string $name): ControllerActionRoute
    {
        $this->httpMethod = $name;
        return $this;
    }

    public function pattern(string $pattern): ControllerActionRoute
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function addMiddleware(callable $middleware): ControllerActionRoute
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function getController(): string
    {
        return $this->controllerClass;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getControllerActionCallable(): array
    {
        return [$this->getController(), $this->getControllerActionMethodString()];
    }

    public function getControllerActionMethodString(): string
    {
        return $this->getAction() . self::ACTION_POSTFIX;
    }

    public function injectTo(App $app)
    {
        $r = $app->map([$this->getHttpMethod()], $this->getPattern(), $this->getControllerActionCallable());
        foreach ($this->getMiddlewares() as $middleware) {
            $r->add($middleware);
        }
    }

    public function jsonSerialize()
    {
        return [
            'httpMethod' => $this->getHttpMethod(),
            'pattern' => $this->getPattern(),
            'controller' => $this->getController(),
            'action' => $this->getAction(),
            'middlewares' => $this->jsonSerializeMiddlewares()
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