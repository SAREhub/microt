<?php

namespace SAREhub\Microt\Util;


use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use Slim\Http\Response;

class ValidatorHelper
{
    /**
     * @param Validator $validator
     * @param mixed $data
     * @return bool
     * @throws DataValidationException
     */
    public function assert(Validator $validator, $data)
    {
        try {
            $validator->assert($data);
            return true;
        } catch (NestedValidationException $e) {
            throw DataValidationException::createFromRespectException($e);
        }
    }

    public function createBadRequestJsonResponse($message, DataValidationException $exception, Response $response)
    {
        return JsonResponse::wrap($response)->badRequest($message, $exception->getErrorDetails());
    }

    public function validate(Validator $validator, $data, &$errors): bool
    {
        try {
            $errors = null; // clear last errors.
            return $validator->assert($data);
        } catch (NestedValidationException $e) {
            $errors = $e->getMessages();
            return false;
        }
    }


}