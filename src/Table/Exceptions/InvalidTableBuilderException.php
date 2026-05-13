<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Exceptions;

use RuntimeException;

final class InvalidTableBuilderException extends RuntimeException
{
    /**
     * @param array<string> $violations
     */
    public static function withViolations(array $violations): self
    {
        $list = implode('; ', $violations);

        return new self(sprintf('TableBuilder validation failed: %s', $list));
    }

    public static function forMissingRowId(string $rowId): self
    {
        return new self(
            sprintf('Row is missing rowId field "%s". Check the rowId() builder setting.', $rowId),
        );
    }
}
