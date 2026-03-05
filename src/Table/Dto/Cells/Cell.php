<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Table\Interfaces\Cells\Cell as CellInterface;

abstract class Cell implements CellInterface
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
