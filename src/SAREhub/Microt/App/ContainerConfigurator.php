<?php


namespace SAREhub\Microt\App;


use DI\ContainerBuilder;

interface ContainerConfigurator
{
    public function configure(ContainerBuilder $builder);
}
