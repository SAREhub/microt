<?php

namespace SAREhub\Microt\HealthCheck;

interface HealthCheckCommand
{
    public function perform(): HealthCheckResult;
}