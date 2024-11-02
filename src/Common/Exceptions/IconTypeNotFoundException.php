<?php

namespace Patrikjak\Utils\Common\Exceptions;

use Exception;
use Patrikjak\Utils\Common\Enums\IconType;

class IconTypeNotFoundException extends Exception
{
    public function __construct(IconType $type)
    {
        parent::__construct(sprintf('Icon type %s not found', $type->value));
    }
}