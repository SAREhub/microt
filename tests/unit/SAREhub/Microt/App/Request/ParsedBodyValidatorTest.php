<?php

namespace SAREhub\Microt\App\Request;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Validator;
use SAREhub\Microt\Test\App\HttpHelper;
use SAREhub\Microt\Util\DataValidationException;

class ParsedBodyValidatorTest extends TestCase
{

    /**
     * @var RequestValidator
     */
    private $requestValidator;

    protected function setUp()
    {
        $validator = Validator::key("test", Validator::notBlank());
        $this->requestValidator = new ParsedBodyValidator($validator);
    }

    public function testAssertWhenDataValid()
    {
        $request = HttpHelper::requestWithJson([
            "test" => "value"
        ]);
        $this->assertTrue($this->requestValidator->assert($request));
    }

    public function testAssertWhenDataNotValid()
    {
        $request = $request = HttpHelper::requestWithJson([
            "other" => "value"
        ]);

        $this->expectException(DataValidationException::class);

        $this->requestValidator->assert($request);
    }
}
