<?php

namespace Patrikjak\Utils\Common\Exceptions;

use Exception;

class InvalidTypeException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid type');
    }
}