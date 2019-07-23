<?php


namespace SAREhub\Microt\App;


interface AppRunOptionsProvider
{
    public function get(): AppRunOptions;
}