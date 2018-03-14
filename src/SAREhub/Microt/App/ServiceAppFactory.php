<?php


namespace SAREhub\Microt\App;


class ServiceAppFactory
{
    /**
     * Creates very basic app.
     * @return ServiceApp
     */
    public function createBasic(): ServiceApp
    {
        return $this->create(new BasicContainerConfigurator());
    }

    public function create(ContainerConfigurator $containerConfigurator): ServiceApp
    {
        return new ServiceApp($containerConfigurator);
    }
}