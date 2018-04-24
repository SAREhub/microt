<?php


namespace SAREhub\Microt\App\Controller;


use SAREhub\Microt\App\Middleware\MiddlewareInjector;
use SAREhub\Microt\App\Route\RouteMiddlewareInjector;
use Slim\App;

class ControllerActionRoute implements MiddlewareInjector, \JsonSerializable
{
    const ACTION_POSTFIX = 'Action';

    private $controllerClass;
    private $action;
    private $httpMethod;
    private $pattern = '';
    private $middlewareInjector = null;

    public static function post(string $pattern = '', string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('POST')->pattern($pattern)->action($action);
    }

    public static function get(string $pattern = '', string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('GET')->pattern($pattern)->action($action);
    }

    public static function put(string $pattern = '', string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('PUT')->pattern($pattern)->action($action);
    }

    public static function patch(string $pattern = '', string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('PATCH')->pattern($pattern)->action($action);
    }

    public static function delete(string $pattern = '', string $action): ControllerActionRoute
    {
        return self::route()->httpMethod('DELETE')->pattern($pattern)->action($action);
    }

    public static function options(string $pattern = '', string $action): ControllerActionRoute
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

    public function middlewareInjector(RouteMiddlewareInjector $injector): ControllerActionRoute
    {
        $this->middlewareInjector = $injector;
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

    public function getMiddlewareInjector(): RouteMiddlewareInjector
    {
        return $this->middlewareInjector;
    }

    public function hasMiddlewareInjector(): bool
    {
        return $this->middlewareInjector !== null;
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
        if ($this->hasMiddlewareInjector()) {
            $this->getMiddlewareInjector()->injectTo($r);
        }
    }

    public function jsonSerialize()
    {
        $middlewareInjector = $this->getMiddlewareInjector();
        return [
            'httpMethod' => $this->getHttpMethod(),
            'pattern' => $this->getPattern(),
            'controller' => $this->getController(),
            'action' => $this->getAction(),
            'middlewareInjector' => $middlewareInjector instanceof \JsonSerializable ?
                $middlewareInjector : get_class($middlewareInjector)
        ];
    }
}