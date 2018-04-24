<?php


namespace SAREhub\Microt\App\Middleware;


use Slim\App;

class MultiMiddlewareInjector implements MiddlewareInjector
{

    /**
     * @var MiddlewareInjector[]
     */
    private $injectors;

    public function __construct(array $injectors)
    {
        $this->injectors = $injectors;
    }

    public function injectTo(App $app)
    {
        foreach ($this->injectors as $i) {
            $i->injectTo($app);
        }
    }
}