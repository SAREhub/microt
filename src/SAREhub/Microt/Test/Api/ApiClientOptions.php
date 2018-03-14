<?php


namespace SAREhub\Microt\Test\Api;


class ApiClientOptions
{
    private $host = "localhost";
    private $port = 10000;

    private $authUser = "root";
    private $authPassword = "test";

    private $apiVersion = "v1";

    public static function newInstance(): self
    {
        return new self();
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): ApiClientOptions
    {
        $this->host = $host;
        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): ApiClientOptions
    {
        $this->port = $port;
        return $this;
    }

    public function getAuthUser(): string
    {
        return $this->authUser;
    }

    public function setAuthUser(string $authUser): ApiClientOptions
    {
        $this->authUser = $authUser;
        return $this;
    }

    public function getAuthPassword(): string
    {
        return $this->authPassword;
    }

    public function setAuthPassword(string $authPassword): ApiClientOptions
    {
        $this->authPassword = $authPassword;
        return $this;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public function setApiVersion(string $apiVersion): ApiClientOptions
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }
}
