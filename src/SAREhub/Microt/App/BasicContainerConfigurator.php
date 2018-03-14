<?php


namespace SAREhub\Microt\App;


use DI\Bridge\Slim\ControllerInvoker;
use DI\ContainerBuilder;
use SAREhub\Microt\Logger\AppLoggerProvider;
use function DI\create;
use function DI\factory;
use function DI\get;

class BasicContainerConfigurator implements ContainerConfigurator
{
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
            "settings.responseChunkSize" => 4096,
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
