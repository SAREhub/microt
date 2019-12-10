<?php


namespace SAREhub\Microt\App\Auth\ApiKey;


use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\Commons\Misc\InvokableProvider;
use SAREhub\Commons\Secret\SecretValueProvider;

class ApiKeyAuthMiddlewareProvider extends InvokableProvider
{
    const DEFAULT_ENV_API_KEY_SECRET = "API_AUTH_APIKEY_SECRET";

    /**
     * @var SecretValueProvider
     */
    private $secretValueProvider;
    /**
     * @var string
     */
    private $secretNameEnvVar;

    public function __construct(SecretValueProvider $secretValueProvider, string $secretNameEnvVar = self::DEFAULT_ENV_API_KEY_SECRET)
    {
        $this->secretValueProvider = $secretValueProvider;
        $this->secretNameEnvVar = $secretNameEnvVar;
    }

    public function get()
    {
        $secretName = EnvironmentHelper::getRequiredVar($this->secretNameEnvVar);
        return new ApiKeyAuthMiddleware($this->secretValueProvider->get($secretName));
    }
}
