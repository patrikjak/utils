<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Exceptions;

class MissingTableParametersException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Table parameters are missing for paginated table', 500);
    }
}