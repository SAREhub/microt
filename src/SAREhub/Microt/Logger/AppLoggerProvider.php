<?php


namespace SAREhub\Microt\Logger;


use Monolog\Handler\AbstractHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Pimple\Container;
use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\Microt\App\AppBootstrap;
use SAREhub\Microt\App\ServiceProvider;

class AppLoggerProvider implements ServiceProvider
{
    const LOGGING_LEVEL = "APP_LOGGING_LEVEL";

    public function register(Container $c)
    {
        $handlers = $this->createHandlers($c);
        $processors = $this->createProcessors($c);
        $logger = new Logger($c[AppBootstrap::APP_NAME_ENTRY], $handlers, $processors);
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof AbstractHandler) {
                $handler->setLevel(EnvironmentHelper::getVar(self::LOGGING_LEVEL, "debug"));
            }
        }
        $c[self::class] = $logger;
    }

    protected function createHandlers(Container $c): array
    {
        return [$this->createStdoutHandler($c)];
    }

    protected function createStdoutHandler(Container $c): StreamHandler
    {
        $output = new StreamHandler('php://stdout');
        $formatter = new StandardLogFormatter();
        $output->setFormatter($formatter);
        return $output;
    }

    protected function createProcessors(Container $c): array
    {
        return [
            $this->createRequestIdProcessor($c),
            new PsrLogMessageProcessor()
        ];
    }

    protected function createRequestIdProcessor(Container $c): RequestIdProcessor
    {
        if ($c['request']->hasHeader('X-Request-ID')) {
            return new RequestIdProcessor($c['request']->getHeader('X-Request-ID')[0]);
        }

        return new RequestIdProcessor(0);
    }
}