<?php

namespace SAREhub\Microt\App;

use Slim\App;
use Slim\Middleware\HttpBasicAuthentication;

class BasicAuthMiddlewareInjector implements MiddlewareInjector
{
    public function injectTo(App $app)
    {
        $options = $app->getContainer()->get(BasicAuthOptionsProvider::class);
        $app->add(new HttpBasicAuthentication($options));
    }
}