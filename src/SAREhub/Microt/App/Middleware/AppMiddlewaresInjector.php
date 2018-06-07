<?php


namespace SAREhub\Microt\App\Middleware;


use Slim\App;

/**
 * Injects middlewares and middleware injectors registered in container on given key
 */
class AppMiddlewaresInjector implements MiddlewareInjector
{
    /**
     * @var string
     */
    private $key;

    public function __construct(string $middlewaresKey)
    {
        $this->key = $middlewaresKey;
    }

    public function injectTo(App $app)
    {
        $middlewares = $app->getContainer()->get($this->key);
        $injector = new MultiMiddlewareInjector($middlewares);
        $injector->injectTo($app);
    }
}