<?php

namespace SAREhub\Microt\App\Auth;

use SAREhub\Microt\App\Middleware\MiddlewareInjector;
use Slim\App;
use Slim\Middleware\HttpBasicAuthentication;

class BasicAuthMiddlewareInjector implements MiddlewareInjector
{
    /**
     * @var array
     */
    private $authOptions;

    public function __construct(array $authOptions)
    {
        $this->authOptions = $authOptions;
    }

    public function injectTo(App $app)
    {
        $app->add(new HttpBasicAuthentication($this->authOptions));
    }
}