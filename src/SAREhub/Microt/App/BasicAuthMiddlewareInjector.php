<?php

namespace SAREhub\Microt\App;

use Slim\App;
use Slim\Middleware\HttpBasicAuthentication;

class BasicAuthMiddlewareInjector implements MiddlewareInjector
{
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