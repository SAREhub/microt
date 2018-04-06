<?php


namespace SAREhub\Microt\Logger;


use Monolog\Handler\AbstractHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Pimple\Container;
use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\Commons\Misc\InvokableProvider;

class AppLoggerProvider extends InvokableProvider
{
    const ENV_LOGGING_LEVEL = "APP_LOGGING_LEVEL";
    const DEFAULT_LOGGING_LEVEL = "debug";

    const ENV_LOGGING_STREAM = "APP_LOGGING_STREAM";
    const DEFAULT_LOGGING_STREAM = "php://stdout";

    const LOGGER_NAME = "app";

    public function get()
    {
        $handlers = $this->createHandlers();
        $processors = $this->createProcessors();
        $logger = new Logger(self::LOGGER_NAME, $handlers, $processors);
        $loggingLevel = EnvironmentHelper::getVar(self::ENV_LOGGING_LEVEL, self::DEFAULT_LOGGING_LEVEL);
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof AbstractHandler) {
                $handler->setLevel($loggingLevel);
            }
        }

        return $logger;
    }

    private function createHandlers(): array
    {
        return [$this->createStdoutHandler()];
    }

    private function createStdoutHandler(): StreamHandler
    {
        $stream = EnvironmentHelper::getVar(self::ENV_LOGGING_STREAM, self::DEFAULT_LOGGING_STREAM);
        $output = new StreamHandler($stream);
        $formatter = new StandardLogFormatter();
        $formatter->includeStacktraces(true);
        $output->setFormatter($formatter);
        return $output;
    }

    private function createProcessors(): array
    {
        return [
            new PsrLogMessageProcessor()
        ];
    }

    private function createRequestIdProcessor(Container $c): RequestIdProcessor
    {
        if ($c['request']->hasHeader('X-Request-ID')) {
            return new RequestIdProcessor($c['request']->getHeader('X-Request-ID')[0]);
        }

        return new RequestIdProcessor(0);
    }
}