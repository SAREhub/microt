<?php


namespace SAREhub\Microt\App;

use DI\ContainerBuilder;

class CompiledContainerConfigurator implements ContainerConfigurator
{
    const COMPILED_CONTAINER_CLASS_NAME = "CompiledContainer";
    const COMPILED_CONTAINER_DIRECTORY = "/app/compiled_container";
    const COMPILED_CONTAINER_PATH = self::COMPILED_CONTAINER_DIRECTORY . "/" . self::COMPILED_CONTAINER_CLASS_NAME . ".php";

    public static function create(ContainerConfigurator $containerConfigurator): ContainerConfigurator
    {
        $compiledContainerConfigurator = new CompiledContainerConfigurator();
        if (CompiledContainerConfigurator::isCompiled()) {
            return $compiledContainerConfigurator;
        }
        return new ChainContainerConfigurator([$compiledContainerConfigurator, $containerConfigurator]);
    }

    public function configure(ContainerBuilder $builder)
    {
        $builder->useAutowiring(true);
        $builder->useAnnotations(false);
        $builder->enableCompilation(self::COMPILED_CONTAINER_DIRECTORY, self::COMPILED_CONTAINER_CLASS_NAME);
    }

    public static function isCompiled(): bool
    {
        return file_exists(self::COMPILED_CONTAINER_PATH);
    }

}