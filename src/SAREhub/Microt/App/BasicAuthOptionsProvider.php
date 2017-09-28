<?php

namespace SAREhub\Microt\App;


use Pimple\Container;
use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\DockerUtil\Secret\SecretHelper;
use SAREhub\Microt\Util\JsonResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class BasicAuthOptionsProvider implements ServiceProvider
{
    const ENV_API_ROOT_PASSWORD_SECRET = 'API_ROOT_PASSWORD_SECRET';

    public function register(Container $c)
    {
        $c[self::class] = [
            "secure" => false,
            "users" => $this->getAuthUsers($c),
            "error" => $this->getErrorCallback()
        ];
    }

    private function getAuthUsers(Container $c): array
    {
        return [
            "root" => $this->getSecretHelper($c)->getValue(EnvironmentHelper::getVar(self::ENV_API_ROOT_PASSWORD_SECRET))
        ];
    }

    private function getSecretHelper(Container $c): SecretHelper
    {
        return $c[SecretHelperProvider::class];
    }

    private function getErrorCallback(): callable
    {
        return function (Request $req, Response $resp, array $arguments) {
            return JsonResponse::wrap($resp)->error($arguments['message'], [], 401);
        };
    }
}