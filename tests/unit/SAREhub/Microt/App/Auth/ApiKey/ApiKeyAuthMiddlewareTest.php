<?php

namespace SAREhub\Microt\App\Auth\ApiKey;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Test\CallableMock;
use SAREhub\Microt\Test\App\HttpHelper;

class ApiKeyAuthMiddlewareTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $apiKey;

    /**
     * @var ApiKeyAuthMiddleware
     */
    private $middleware;

    protected function setUp()
    {
        $this->apiKey = "test_api_key";
        $this->middleware = new ApiKeyAuthMiddleware($this->apiKey);
    }

    public function testInvokeWhenPassed()
    {
        $request = HttpHelper::requestWithQuery([
            ApiKeyAuthMiddleware::QP_APIKEY => $this->apiKey
        ]);
        $response = HttpHelper::response();
        $next = CallableMock::create();

        $expectedResponse = HttpHelper::response();
        $next->expects("__invoke")->with($request, $response)->andReturn($expectedResponse);

        $currentResponse = ($this->middleware)($request, $response, $next);
        $this->assertSame($expectedResponse, $currentResponse);
    }

    public function testInvokeWhenNotPassed()
    {
        $request = HttpHelper::requestWithQuery([
            ApiKeyAuthMiddleware::QP_APIKEY => "invalid_api_key"
        ]);
        $response = HttpHelper::response();
        $next = CallableMock::create();

        $next->expects("__invoke")->never();

        $response = ($this->middleware)($request, $response, $next);

        $this->assertEquals(401, $response->getStatusCode());
    }
}
