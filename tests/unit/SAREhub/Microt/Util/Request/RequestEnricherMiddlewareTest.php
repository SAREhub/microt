<?php

namespace SAREhub\Microt\Util\Request;


use PHPUnit\Framework\TestCase;
use SAREhub\Microt\Test\App\HttpHelper;
use SAREhub\Microt\Test\CallableMock;


class RequestEnricherMiddlewareTest extends TestCase
{
    public function testCall()
    {
        $enricher = \Mockery::mock(RequestEnricher::class);
        $middleware = new RequestEnricherMiddleware($enricher);
        $inRequest = HttpHelper::request();
        $inResponse = HttpHelper::response();
        $next = CallableMock::create();

        $enrichedRequest = HttpHelper::request();
        $enricher->expects("enrich")->withArgs([$inRequest])->andReturn($enrichedRequest);

        $nextResponse = HttpHelper::response();
        $next->expects("__invoke")->withArgs([$enrichedRequest, $inResponse])->andReturn($nextResponse);

        $currentResponse = $middleware($inRequest, $inResponse, $next);

        $this->assertSame($nextResponse, $currentResponse, "middleware response === next()");
    }
}
