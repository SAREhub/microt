<?php

namespace SAREhub\Microt\Account;

use Pimple\Container;

abstract class ServiceAccountRegistry
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function register(string $accountId)
    {
        if (!$this->isRegistered($accountId)) {
            $this->onRegister($accountId);
        }
    }

    protected abstract function onRegister(string $accountId);

    public function unregister(string $accountId)
    {
        if ($this->isRegistered($accountId)) {
            $this->onUnregister($accountId);
        }
    }

    protected abstract function onUnregister(string $accountId);

    public abstract function isRegistered(string $accountId): bool;

    public abstract function getList(): array;

    public function getContainer(): Container
    {
        return $this->container;
    }
}