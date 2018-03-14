<?php
/**
 * Copyright 2017 SARE S.A
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 *
 */

namespace SAREhub\Microt\Util;

use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Http\Response;

class ValidatorHelperTest extends TestCase
{

    private $validator;

    /**
     * @var ValidatorHelper
     */
    private $helper;

    protected function setUp()
    {
        $this->validator = Validator::notBlank();
        $this->helper = new ValidatorHelper();
    }

    public function testValidateWhenValid()
    {
        $this->assertTrue($this->helper->validate($this->validator, 'data', $errors), 'validate');
        $this->assertNull($errors);
    }

    public function testValidateWhenNotValid()
    {
        $data = '';
        $this->assertFalse($this->helper->validate($this->validator, $data, $errors), 'validate');
        $expectedErrors = $this->extractExpectedErrors($data);
        $this->assertNotNull($errors, 'errors');
        $this->assertEquals($expectedErrors, $errors);
    }

    public function testValidateWhenErrorsNotEmptyAndValid()
    {
        $errors = ['error'];
        $this->helper->validate($this->validator, 'data', $errors);
        $this->assertNull($errors, 'errors');
    }

    public function testAssertWhenIsValidThenReturnTrue()
    {
        $data = "not_blank";
        $this->assertTrue($this->helper->assert($this->validator, $data));
    }

    public function testAssertWhenIsNotValidThenThrowException()
    {
        $data = "";
        try {
            $this->helper->assert($this->validator, $data);
        } catch (DataValidationException $e) {
            $expectedErrors = $this->extractExpectedErrors($data);
            $this->assertEquals($expectedErrors, $e->getErrors());
            return;
        }

        $this->fail("expected DataValidationException throws");
    }

    public function testCreateBadRequestJsonResponse()
    {
        $message = "test_message";
        $exception = \Mockery::mock(DataValidationException::class);
        $expectedErrors = ["test_errors"];
        $exception->expects("getErrors")->andReturn($expectedErrors);
        $response = $this->helper->createBadRequestJsonResponse($message, $exception, new Response());
        $response->getBody()->rewind();
        $expectedJson = [
            "message" => $message,
            "details" => $expectedErrors
        ];

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode($expectedJson, JSON_PRETTY_PRINT), $response->getBody()->getContents());
    }

    private function extractExpectedErrors($data): array
    {
        try {
            $this->validator->assert($data);
            throw new \LogicException("expected data is not valid");
        } catch (NestedValidationException $e) {
            return $e->getMessages();
        }
    }
}
