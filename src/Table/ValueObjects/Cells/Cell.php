<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Cells;

use Patrikjak\Utils\Table\Contracts\Cells\Cell as CellContract;

abstract readonly class Cell implements CellContract
{
    public function __construct(
        public string $value,
        public ?int $maxLength = null,
        public bool $noTruncation = false,
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
