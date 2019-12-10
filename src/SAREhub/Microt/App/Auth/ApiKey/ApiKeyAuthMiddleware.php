<?php


namespace SAREhub\Microt\App\Auth\ApiKey;


use SAREhub\Microt\App\Middleware\MiddlewareInjector;
use SAREhub\Microt\Util\JsonResponse;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class ApiKeyAuthMiddleware implements MiddlewareInjector
{
    const QP_APIKEY = "apiKey";

    /**
     * @var string
     */
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function injectTo(App $app)
    {
        $app->add($this);
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        if ($request->getQueryParam(self::QP_APIKEY, "") !== $this->apiKey) {
            return JsonResponse::wrap($response)->error("Invalid apiKey", [], 401);
        }

        return $next($request, $response);
    }


}
