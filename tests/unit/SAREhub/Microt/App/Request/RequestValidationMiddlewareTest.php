<?php

namespace SAREhub\Microt\App\Request;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Test\CallableMock;
use SAREhub\Microt\Test\App\HttpHelper;
use SAREhub\Microt\Util\DataValidationException;
use SAREhub\Microt\Util\ValidatorHelper;
use Slim\Exception\NotFoundException;

class RequestValidationMiddlewareTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | RequestValidator
     */
    private $validator;

    /**
     * @var MockInterface | ValidatorHelper
     */
    private $helper;

    /**
     * @var RequestValidationMiddleware
     */
    private $middleware;

    protected function setUp()
    {
        $this->validator = \Mockery::mock(RequestValidator::class);
        $this->helper = \Mockery::mock(ValidatorHelper::class);
        $this->middleware = new RequestValidationMiddleware($this->validator, $this->helper);
    }

    public function testInvokeWhenValidationPass()
    {
        $request = HttpHelper::requestWithRouteArguments([]);
        $response = HttpHelper::response();
        $next = CallableMock::create();

        $this->validator->expects("assert")->with($request);
        $next->expects("__invoke");

        ($this->middleware)($request, $response, $next);
    }

    public function testInvokeWhenValidationFail()
    {
        $request = HttpHelper::requestWithRouteArguments([]);
        $response = HttpHelper::response();
        $next = CallableMock::create();

        $exception = new DataValidationException("test", ["test_detail" => "test_value"]);
        $this->validator->expects("assert")->with($request)->andThrow($exception);
        $expectedResponse = HttpHelper::response();
        $this->helper->expects("createBadRequestJsonResponse")
            ->with("Bad request", $exception, $response)
            ->andReturn($expectedResponse);
        $next->expects("__invoke")->never();

        $currentResponse = ($this->middleware)($request, $response, $next);
        $this->assertSame($expectedResponse, $currentResponse);
    }

    public function testInvokeWhenRequestWithoutRoute()
    {
        $request = HttpHelper::request();
        $response = HttpHelper::response();
        $next = CallableMock::create();

        $this->validator->expects("assert")->never();
        $next->expects("__invoke")->never();

        $this->expectException(NotFoundException::class);

        ($this->middleware)($request, $response, $next);
    }
}
