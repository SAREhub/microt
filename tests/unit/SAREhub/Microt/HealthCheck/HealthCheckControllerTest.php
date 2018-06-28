<?php

namespace SAREhub\Microt\HealthCheck;

use Mockery\MockInterface;
use SAREhub\Microt\Test\App\ControllerTestCase;
use SAREhub\Microt\Test\App\HttpHelper;

class HealthCheckControllerTest extends ControllerTestCase
{

    /**
     * @var HealthCheckCommand | MockInterface
     */
    private $command;

    /**
     * @var HealthCheckController
     */
    private $controller;

    protected function setUp()
    {
        parent::setUp();
        $this->command = \Mockery::mock(HealthCheckCommand::class);
        $this->controller = new HealthCheckController($this->command);
    }

    /**
     * @dataProvider resultProvider
     * @param int $expectedStatusCode
     * @param HealthCheckResult $result
     */
    public function testHealthAction(int $expectedStatusCode, HealthCheckResult $result)
    {
        $this->command->expects("perform")->andReturn($result);
        $response = $this->callAction($this->controller, "health", HttpHelper::request());
        $this->assertJsonResponse($expectedStatusCode, $result, $response);
    }

    public function resultProvider()
    {
        $details = ["detail"];
        return [
            "pass" => [HealthCheckResult::DEFAULT_PASS_STATUS_CODE, HealthCheckResult::createPass($details)],
            "warn" => [HealthCheckResult::DEFAULT_WARN_STATUS_CODE, HealthCheckResult::createWarn($details)],
            "fail" => [HealthCheckResult::DEFAULT_FAIL_STATUS_CODE, HealthCheckResult::createFail($details)]
        ];
    }
}
