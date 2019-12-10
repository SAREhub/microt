<?php


namespace SAREhub\Microt\Logger;

use Monolog\Formatter\JsonFormatter;

class StandardLogFormatter extends JsonFormatter
{

    protected function normalize($data, $depth = 0)
    {
        if ($data instanceof \DateTime) {
            return $data->format(DATE_ATOM);
        }

        return parent::normalize($data);
    }
}
