<?php


namespace SAREhub\Microt\Util;


use Exception;
use Respect\Validation\Exceptions\NestedValidationException;
use Throwable;

class DataValidationException extends Exception implements \JsonSerializable
{
    /**
     * @var array
     */
    private $errorDetails;

    /**
     * @param string $message
     * @param mixed $errorDetails
     * @param Throwable|null $previous
     */
    public function __construct(string $message, $errorDetails = null, ?Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
        $this->errorDetails = $errorDetails;
    }

    public static function createFromRespectException(NestedValidationException $exception)
    {
        return new self($exception->getMessage(), $exception->getMessages(), $exception);
    }

    public function getErrorDetails(): array
    {
        return $this->errorDetails;
    }

    public function jsonSerialize()
    {
        return [
            "message" => $this->getMessage(),
            "details" => $this->getErrorDetails()
        ];
    }
}