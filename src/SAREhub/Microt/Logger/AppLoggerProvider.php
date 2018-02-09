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
    const ENV_LOGGING_LEVEL = "APP_LOGGING_LEVEL";
    const ENV_LOGGING_STREAM = "APP_LOGGING_STREAM";

    const DEFAULT_LOGGING_LEVEL = "debug";

    public function register(Container $c)
    {
        $handlers = $this->createHandlers($c);
        $processors = $this->createProcessors($c);
        $logger = new Logger($c[AppBootstrap::APP_NAME_ENTRY], $handlers, $processors);
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof AbstractHandler) {
                $handler->setLevel(EnvironmentHelper::getVar(self::ENV_LOGGING_LEVEL, self::DEFAULT_LOGGING_LEVEL));
            }
        }
        $c[self::class] = $logger;
    }

    protected function createHandlers(Container $c): array
    {
        return [$this->createStdoutHandler($c)];
    }

    protected function createStdoutHandler(): StreamHandler
    {
        $output = new StreamHandler(EnvironmentHelper::getVar(self::ENV_LOGGING_STREAM, "php://stdout"));
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