<?php

namespace SAREhub\Microt\Account;

abstract class ServiceAccountRegistry
{

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

}