<?php


namespace SAREhub\Microt\App\Request;


use DI\Definition\Definition;
use DI\Definition\Helper\DefinitionHelper;
use Respect\Validation\Validatable;
use function DI\create;
use function DI\factory;

class ValidationDefinitionHelper
{
    public static function allOfMiddleware(array $requestValidators)
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

    /**
     * @param Validatable|Definition|DefinitionHelper $validator
     * @return Definition|DefinitionHelper
     */
    public static function attributes($validator)
    {
        return create(AttributesValidator::class)->constructor(self::validatorDef($validator));
    }

    /**
     * @param Validatable|Definition|DefinitionHelper $validator
     * @return Definition|DefinitionHelper
     */
    public static function queryParams($validator)
    {
        return create(QueryParamsValidator::class)->constructor(self::validatorDef($validator));
    }

    /**
     * @param Validatable|Definition|DefinitionHelper $validator
     * @return Definition|DefinitionHelper
     */
    public static function parsedBody($validator)
    {
        return create(ParsedBodyValidator::class)->constructor(self::validatorDef($validator));
    }

    private static function validatorDef($validator)
    {
        if ($validator instanceof Validatable) {
            return factory(function () use ($validator) {
                return $validator;
            });
        }
        return $validator;
    }
}
