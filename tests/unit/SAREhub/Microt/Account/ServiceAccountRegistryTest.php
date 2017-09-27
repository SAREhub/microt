<?php

namespace SAREhub\Microt\Account;

use Pimple\Container;
use PHPUnit\Framework\TestCase;

class DummyAccountRegistry extends ServiceAccountRegistry
{
    private $list = [];

    public $registerCounter = 0;

    public $accountId = '';

    public $registered = false;

    protected function onRegister(string $accountId)
    {
        $this->registerCounter++;
        $this->accountId = $accountId;
        $this->registered = true;
    }

    protected function onUnregister(string $accountId)
    {
        $this->registerCounter--;
        $this->accountId = $accountId;
        $this->registered = false;
    }

    public function isRegistered(string $accountId): bool
    {
        return $this->registered;
    }

    public function getList(): array
    {
        return $this->list;
    }

}

class ServiceAccountRegistryTest extends TestCase
{
    public function testOnRegisterThenRegistered()
    {
        $register = new DummyAccountRegistry(new Container());
        $register->register('test');

        $this->assertEquals(1, $register->registerCounter);
        $this->assertEquals('test', $register->accountId);
    }

    public function testOnRegisterWhenRegistered()
    {
        $register = new DummyAccountRegistry(new Container());
        $register->register('test');
        $register->register('test');

        $this->assertEquals(1, $register->registerCounter);
        $this->assertEquals('test', $register->accountId);
    }

    public function testOnUnregisterThenUnregistered()
    {
        $register = new DummyAccountRegistry(new Container());
        $register->register('test');
        $register->unregister('test');

        $this->assertEquals(0, $register->registerCounter);
        $this->assertEquals('test', $register->accountId);
    }

    public function testOnUnregisterWhenUnregistered()
    {
        $register = new DummyAccountRegistry(new Container());
        $register->register('test');
        $register->unregister('test');
        $register->unregister('test');

        $this->assertEquals(0, $register->registerCounter);
        $this->assertEquals('test', $register->accountId);
    }
}
