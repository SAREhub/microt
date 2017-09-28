<?php

namespace SAREhub\Microt\App;


use Pimple\Container;
use SAREhub\DockerUtil\Secret\SecretHelper;

class SecretHelperProvider implements ServiceProvider
{

    public function register(Container $c)
    {
        $c[self::class] = function () {
            return new SecretHelper();
        };
    }
}