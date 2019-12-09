<?php


namespace SAREhub\Microt\App\Auth\ApiKey;


use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\Commons\Secret\SecretValueProvider;

class ApiKeyAuthMiddlewareProvider extends InvokableProvider
{
    const ENV_API_KEY_SECRET = "API_AUTH_APIKEY";

    /**
     * @var SecretValueProvider
     */
    private $secretValueProvider;

    public function __construct(SecretValueProvider $secretValueProvider)
    {
        $this->secretValueProvider = $secretValueProvider;
    }

    public function get()
    {
        $secretName = EnvironmentHelper::getRequiredVar(self::ENV_API_KEY_SECRET);
        return new ApiKeyAuthMiddleware($this->secretValueProvider->get($secretName));
    }
}
