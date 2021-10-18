<?php

namespace Alaa\PEPlist\Exceptions;

use Throwable;

class PlistException extends \Exception
{
    public function __construct($message = "Unknown Error", $code = 100, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}