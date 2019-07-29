<?php


namespace SAREhub\Microt\App\Request;


use Respect\Validation\Validator;
use function DI\create;

class ValidationDefinitionHelper
{
    public static function attributes(Validator $validator)
    {
        return create(AttributesValidator::class)->constructor($validator);
    }

    public static function queryParams(Validator $validator)
    {
        return create(QueryParamsValidator::class)->constructor($validator);
    }

    public static function allOf(array $requestValidators)
    {
        return create(AllOfRequestValidator::class)->constructor($requestValidators);
    }

    public static function middleware($requestValidatorDef)
    {
        return create(RequestValidationMiddleware::class)->constructor($requestValidatorDef);
    }
}