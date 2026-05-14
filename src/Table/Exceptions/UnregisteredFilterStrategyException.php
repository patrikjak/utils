<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Exceptions;

use RuntimeException;

final class UnregisteredFilterStrategyException extends RuntimeException
{
    public static function forType(string $type): self
    {
        return new self(sprintf('No filter strategy registered for type "%s".', $type));
    }
}
