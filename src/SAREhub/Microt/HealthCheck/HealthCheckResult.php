<?php

namespace SAREhub\Microt\HealthCheck;

class HealthCheckResult implements \JsonSerializable
{
    const PASS_STATUS = "pass";
    const DEFAULT_PASS_STATUS_CODE = 200;

    const WARN_STATUS = "warn";
    const DEFAULT_WARN_STATUS_CODE = 424;

    const FAIL_STATUS = "fail";
    const DEFAULT_FAIL_STATUS_CODE = 503;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $details;

    public function __construct(string $status, int $statusCode, array $details = [])
    {
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->details = $details;
    }

    public static function createPass(array $details = [])
    {
        return new self(self::PASS_STATUS, self::DEFAULT_PASS_STATUS_CODE, $details);
    }

    public static function createWarn(array $details = [])
    {
        return new self(self::WARN_STATUS, self::DEFAULT_WARN_STATUS_CODE, $details);
    }

    public static function createFail(array $details = [])
    {
        return new self(self::FAIL_STATUS, self::DEFAULT_FAIL_STATUS_CODE, $details);
    }

    public function jsonSerialize()
    {
        return [
            "status" => $this->getStatus(),
            "statusCode" => $this->getStatusCode(),
            "details" => $this->getDetails()
        ];
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}