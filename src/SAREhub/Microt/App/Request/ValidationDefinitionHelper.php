<?php


namespace SAREhub\Microt\App\Request;


use function DI\create;

class ValidationDefinitionHelper
{
    public static function allOffMiddleware(array $requestValidators)
    {
        return self::middleware(self::allOf($requestValidators));
    }

    public static function allOf(array $requestValidators)
    {
        return create(AllOfRequestValidator::class)->constructor($requestValidators);
    }

    public static function middleware($requestValidatorDef)
    {
        return create(RequestValidationMiddleware::class)->constructor($requestValidatorDef);
    }

    public static function attributes($validator)
    {
        return create(AttributesValidator::class)->constructor($validator);
    }

    public static function queryParams($validator)
    {
        return create(QueryParamsValidator::class)->constructor($validator);
    }

    public static function parsedBody($validator)
    {
        return create(ParsedBodyValidator::class)->constructor($validator);
    }
}
