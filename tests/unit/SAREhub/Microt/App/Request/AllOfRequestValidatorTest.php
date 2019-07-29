<?php

namespace SAREhub\Microt\App\Request;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SAREhub\Microt\Test\App\HttpHelper;
use SAREhub\Microt\Util\DataValidationException;

class AllOfRequestValidatorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testAssertWhenValid()
    {
        $requestValidator = \Mockery::mock(RequestValidator::class);
        $allOf = new AllOfRequestValidator([$requestValidator]);
        $request = HttpHelper::request();

        $requestValidator->expects("assert")->with($request);

        $this->assertTrue($allOf->assert($request));
    }

    public function testAssertWhenNotValid()
    {
        $requestValidator = \Mockery::mock(RequestValidator::class);
        $allOf = new AllOfRequestValidator([$requestValidator]);
        $request = HttpHelper::request();

        $exception = new DataValidationException("test", ["test_details"]);
        $requestValidator->expects("assert")->with($request)->andThrow($exception);
        $requestValidator->expects("getName")->andReturn("test_name");

        try {
            $allOf->assert($request);
        } catch (DataValidationException $e) {
            $this->assertEquals("Request validation error", $e->getMessage());
            $this->assertEquals(["test_name" => $exception], $e->getErrorDetails());
        }
    }
}
