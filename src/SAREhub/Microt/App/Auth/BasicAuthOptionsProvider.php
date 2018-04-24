<?php

namespace SAREhub\Microt\App\Auth;


use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\DockerUtil\Secret\SecretHelper;
use SAREhub\Microt\Util\JsonResponse;
use Slim\Http\Request;
use Slim\Http\Response;

class BasicAuthOptionsProvider extends InvokableProvider
{
    const ENV_API_ROOT_PASSWORD_SECRET = 'API_ROOT_PASSWORD_SECRET';

    /**
     * @var SecretHelper
     */
    private $secretHelper;

    public function __construct(SecretHelper $secretHelper)
    {
        $this->secretHelper = $secretHelper;
    }

    public function get()
    {
        return [
            "secure" => false,
            "users" => $this->getAuthUsers(),
            "error" => $this->getErrorCallback()
        ];
    }

    private function getAuthUsers(): array
    {
        return [
            "root" => $this->secretHelper->getValue(EnvironmentHelper::getVar(self::ENV_API_ROOT_PASSWORD_SECRET))
        ];
    }

    private function getErrorCallback(): callable
    {
        return function (Request $request, Response $response, array $arguments) {
            return JsonResponse::wrap($response)->error($arguments['message'], [], 401);
        };
    }
}
