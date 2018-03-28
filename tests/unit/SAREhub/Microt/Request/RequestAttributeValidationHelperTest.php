<?php

namespace SAREhub\Microt\Request;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;
use SAREhub\Microt\Test\App\HttpHelper;
use SAREhub\Microt\Util\DataValidationException;
use SAREhub\Microt\Util\ValidatorHelper;

class RequestAttributeValidationHelperTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var MockInterface | ValidatorHelper
     */
    private $validatorHelper;

    protected function setUp()
    {
        $this->validatorHelper = \Mockery::mock(ValidatorHelper::class);
    }

    public function testAssertAndReturnWhenHasValidatorThenValidatorHelperAssert()
    {
        $attribute = "test_attribute";
        $value = "test_value";
        $request = $this->requestWithAttribute($attribute, $value);

        $validator = $this->createValidator();
        $helper = $this->createHelper([$attribute => $validator]);

        $this->validatorHelper->expects("assert")->withArgs([$validator, $value]);
        $helper->assertAndReturnValue($attribute, $request);
    }

    public function testAssertAndReturnWhenHasValidatorAndAttributeIsValidThenReturnValue()
    {
        $attribute = "test_attribute";
        $value = "test_value";
        $request = $this->requestWithAttribute($attribute, $value);

        $helper = $this->createHelper([$attribute => $this->createValidator()]);
        $this->validatorHelper->allows("assert")->andReturn(true);

        $this->assertEquals($value, $helper->assertAndReturnValue($attribute, $request));
    }

    public function testAssertAndReturnWhenHasValidatorAndAttributeIsInvalidThenThrowException()
    {
        $attribute = "test_attribute";
        $value = "test_value";
        $request = $this->requestWithAttribute($attribute, $value);
        $helper = $this->createHelper([$attribute => $this->createValidator()]);
        $this->validatorHelper->allows("assert")->andThrow($this->createDataValidationException());

        $this->expectException(DataValidationException::class);

        $helper->assertAndReturnValue($attribute, $request);
    }

    public function testCreateBadRequestJsonResponse()
    {
        $exception = $this->createDataValidationException();
        $response = HttpHelper::response();

        $expectedResponse = HttpHelper::response();
        $this->validatorHelper->expects("createBadRequestJsonResponse")
            ->withArgs(["bad request", $exception, $response])
            ->andReturn($expectedResponse);

        $helper = $this->createHelper([]);
        $this->assertSame($expectedResponse, $helper->createBadRequestJsonResponse($exception, $response));
    }

    private function createHelper(array $validators): RequestAttributeValidationHelper
    {
        return new RequestAttributeValidationHelper($this->validatorHelper, $validators);
    }

    private function requestWithAttribute(string $name, $value)
    {
        return HttpHelper::requestWithAttribute($name, $value);
    }

    private function createValidator()
    {
        return \Mockery::mock(Validator::class);
    }

    private function createDataValidationException(): DataValidationException
    {
        return \Mockery::mock(DataValidationException::class);
    }
}
