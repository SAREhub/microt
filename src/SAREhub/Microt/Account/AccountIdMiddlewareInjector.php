<?php

namespace SAREhub\Microt\Account;


use Pimple\Container;
use SAREhub\Microt\App\MiddlewareInjector;
use Slim\App;

class AccountIdMiddlewareInjector implements MiddlewareInjector
{

    public function injectTo(App $app)
    {
        /** @var Container $c */
        $c = $app->getContainer();
        $app->add(new AccountIdMiddleware($c));
    }
}