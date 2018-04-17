<?php


namespace SAREhub\Microt\App;


use DI\Bridge\Slim\ControllerInvoker;
use DI\ContainerBuilder;
use SAREhub\Commons\Misc\EnvironmentHelper;
use SAREhub\Microt\Logger\AppLoggerProvider;
use function DI\create;
use function DI\factory;
use function DI\get;

class BasicContainerConfigurator implements ContainerConfigurator
{
    const ENV_SLIM_RESPONSE_CHUNK_SIZE = "SLIM_RESPONSE_CHUNK_SIZE";
    const DEFAULT_SLIM_RESPONSE_CHUNK_SIZE = 4096;
    /**
     * @var array|null
     */
    private $definitions;

    public function __construct(array $definitions = [])
    {
        $this->definitions = empty($definitions) ? $this->getBasicDefinitions() : $definitions;
    }

    public function configure(ContainerBuilder $builder)
    {
        $builder->useAutowiring(true);
        $builder->useAnnotations(false);
        $builder->addDefinitions($this->definitions);
    }

    private function getBasicDefinitions(): array
    {
        return [
            "settings.displayErrorDetails" => false,
            "settings.determineRouteBeforeAppMiddleware" => true,
            "settings.responseChunkSize" => EnvironmentHelper::getVar(self::ENV_SLIM_RESPONSE_CHUNK_SIZE, self::DEFAULT_SLIM_RESPONSE_CHUNK_SIZE),
            "settings.outputBuffering" => "append",
            "app.logger" => factory(AppLoggerProvider::class),
            "errorHandler" => create(ErrorHandler::class)->constructor(get("app.logger")),
            "phpErrorHandler" => get("errorHandler"),
            "notFoundHandler" => factory(NotFoundHandlerProvider::class),
            "notAllowedHandler" => factory(MethodNotAllowedHandlerProvider::class),
            "foundHandler" => create(RequestRouteAttributesSetterInvoker::class)
                ->constructor(create(ControllerInvoker::class)
                    ->constructor(get('foundHandler.invoker')))
        ];
    }
}
