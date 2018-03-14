<?php


namespace SAREhub\Microt\Util;


use Respect\Validation\Exceptions\NestedValidationException;

class DataValidationException extends \Exception
{

    public function __construct(NestedValidationException $validationException)
    {
        parent::__construct($validationException->getFullMessage(), 400, $validationException);
    }

    public function getErrors(): array
    {
        return $this->getPrevious()->getMessages();
    }
}