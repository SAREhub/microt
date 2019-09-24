<?php


namespace SAREhub\Microt\Test\App;


use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Route;

class HttpHelper
{
    public static function requestWithRouteArguments(array $arguments): Request
    {
        return self::requestWithAttribute("route", self::routeWithArguments($arguments));
    }

    public static function routeWithArguments(array $arguments): Route
    {
        $route = new Route('GET', 'pattern', function () {
        });
        return $route->setArguments($arguments);
    }

    public static function requestWithQuery(array $query): Request
    {
        return self::request()->withQueryParams($query);
    }

    public static function requestWithAttribute(string $name, $value): Request
    {
        return self::request()->withAttribute($name, $value);
    }

    public static function requestWithAttributes(array $attributes): Request
    {
        return self::request()->withAttributes($attributes);
    }

    public static function requestWithJson($data): Request
    {
        return self::requestWithBody(json_encode($data))->withHeader('Content-Type', 'application/json');
    }

    public static function requestWithBody($body = ''): Request
    {
        $req = self::request()->withBody(new RequestBody());
        $req->getBody()->write($body);
        $req->getBody()->rewind();
        return $req;
    }

    public static function request(): Request
    {
        return Request::createFromEnvironment(Environment::mock());
    }

    public static function response(): Response
    {
        return new Response();
    }
}
