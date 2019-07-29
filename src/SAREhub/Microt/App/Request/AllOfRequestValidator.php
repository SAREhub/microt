<?php


namespace SAREhub\Microt\App\Request;


use SAREhub\Microt\Util\DataValidationException;
use Slim\Http\Request;

class AllOfRequestValidator implements RequestValidator
{
    /**
     * @var RequestValidator[]
     */
    private $validators;

    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    public function assert(Request $request): bool
    {
        $errorDetails = $this->assertWithValidators($request);
        if (!empty($errorDetails)) {
            throw new DataValidationException("Request validation error", $errorDetails);
        }

        return true;
    }

    private function assertWithValidators(Request $request): array
    {
        $errorDetails = [];
        foreach ($this->validators as $validator) {
            $exception = $this->assertWithValidator($validator, $request);
            if (!empty($exception)) {
                $errorDetails[$validator->getName()] = $exception;
            }
        }
        return $errorDetails;
    }

    private function assertWithValidator(RequestValidator $validator, Request $request): ?DataValidationException
    {
        try {
            $validator->assert($request);
            return null;
        } catch (DataValidationException $e) {
            return $e;
        }
    }

    public function getName(): string
    {
        return "AllOf";
    }
}