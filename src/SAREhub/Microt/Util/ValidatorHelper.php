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


use Psr\Http\Message\ResponseInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class ValidatorHelper
{
    /**
     * @param Validator $validator
     * @param $data
     * @throws DataValidationException
     */
    public function assert(Validator $validator, $data)
    {
        try {
            $validator->assert($data);
            return true;
        } catch (NestedValidationException $exception) {
            throw new DataValidationException($exception);
        }
    }

    public function createBadRequestJsonResponse($message, DataValidationException $exception, ResponseInterface $response)
    {
        return JsonResponse::wrap($response)->badRequest($message, $exception->getErrors());
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