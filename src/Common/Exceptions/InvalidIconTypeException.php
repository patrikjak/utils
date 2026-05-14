<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Exceptions;

use LogicException;

class InvalidIconTypeException extends LogicException
{
    public function __construct(string $type)
    {
        parent::__construct(sprintf('Invalid icon type: %s', $type));
    }
}
