<?php


namespace SAREhub\Microt\App\Controller;


class RoutePatternHelper
{
    public static function pattern(...$subPatterns): string
    {
        return implode("/", $subPatterns);
    }

    public static function attr(string $name): string
    {
        return '{' . $name . '}';
    }
}