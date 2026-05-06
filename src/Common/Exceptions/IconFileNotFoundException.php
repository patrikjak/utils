<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Exceptions;

use RuntimeException;

class IconFileNotFoundException extends RuntimeException
{
    public function __construct(string $path)
    {
        parent::__construct(sprintf('Icon file not found: %s', $path));
    }
}
