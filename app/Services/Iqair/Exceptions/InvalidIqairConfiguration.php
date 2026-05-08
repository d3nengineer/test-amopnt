<?php

namespace App\Services\Iqair\Exceptions;

use RuntimeException;

class InvalidIqairConfiguration extends RuntimeException
{
    public static function missing(): self
    {
        return new self('IQAir configuration is incomplete.');
    }

    public static function unsupportedLocation(): self
    {
        return new self('IQAir configured location is not supported.');
    }
}
