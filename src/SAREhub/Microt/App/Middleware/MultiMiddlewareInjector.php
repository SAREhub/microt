<?php


namespace SAREhub\Microt\App\Middleware;


use Slim\App;

class MultiMiddlewareInjector implements MiddlewareInjector
{

    /**
     * @var array
     */
    private $injectors;

    /**
     * @param array $injectors Array with mixed MiddlewareInjector and callable entries
     */
    public function __construct(array $injectors)
    {
        $this->injectors = $injectors;
    }

    public function injectTo(App $app)
    {
        foreach ($this->injectors as $injector) {
            if ($injector instanceof MiddlewareInjector) {
                $injector->injectTo($app);
            } else {
                $app->add($injector);
            }
        }
    }
}