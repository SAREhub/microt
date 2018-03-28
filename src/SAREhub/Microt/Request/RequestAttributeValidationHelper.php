<?php


namespace SAREhub\Microt\Request;


use Respect\Validation\Validator;
use SAREhub\Microt\Util\DataValidationException;
use SAREhub\Microt\Util\ValidatorHelper;
use Slim\Http\Request;
use Slim\Http\Response;

class RequestAttributeValidationHelper
{
    /**
     * @var ValidatorHelper
     */
    private $validatorHelper;

    /**
     * @var Validator[]
     */
    private $validators;

    /**
     *
     * @param ValidatorHelper $validatorHelper
     * @param array $validators Array with attributes validators
     */
    public function __construct(ValidatorHelper $validatorHelper, array $validators)
    {
        $this->validatorHelper = $validatorHelper;
        $this->validators = $validators;
    }

    /**
     * @param string $attribute
     * @param Request $request
     * @return mixed
     * @throws DataValidationException
     */
    public function assertAndReturnValue(string $attribute, Request $request)
    {
        $value = $request->getAttribute($attribute);
        $this->validatorHelper->assert($this->getValidator($attribute), $value);
        return $value;
    }

    public function createBadRequestJsonResponse(DataValidationException $e, Response $response): Response
    {
        return $this->validatorHelper->createBadRequestJsonResponse("bad request", $e, $response);
    }

    public function getValidator(string $attribute): Validator
    {
        return $this->validators[$attribute];
    }
}