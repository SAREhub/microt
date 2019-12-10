<?php


namespace SAREhub\Microt\Logger;


class RequestIdProcessor
{

    private $requestId;

    public function __construct(string $requestId)
    {
        $this->requestId = $requestId;
    }

    public function __invoke(array $record): array
    {
        $record['extra']['requestId'] = $this->requestId;
        return $record;
    }
}
