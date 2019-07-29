<?php

namespace SAREhub\Microt\App\Request;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;
use SAREhub\Microt\Test\App\HttpHelper;
use SAREhub\Microt\Util\DataValidationException;

class AttributesValidatorTest extends TestCase
{
    /**
     * @var RequestValidator
     */
    private $requestValidator;

    protected function setUp()
    {
        $validator = Validator::key("test", Validator::notBlank());
        $this->requestValidator = new AttributesValidator($validator);
    }

    public function testAssertWhenDataValid()
    {
        $request = HttpHelper::requestWithRouteArguments([
            "test" => "test_value"
        ]);
        $this->assertTrue($this->requestValidator->assert($request));
    }

    public function testAssertWhenDataNotValid()
    {
        $request = HttpHelper::requestWithRouteArguments([]);

        $this->expectException(DataValidationException::class);

        $this->requestValidator->assert($request);
    }
}
