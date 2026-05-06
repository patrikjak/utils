<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Cells;

use Patrikjak\Utils\Table\Contracts\Cells\Cell as CellContract;
use Patrikjak\Utils\Table\Enums\Cells\CellType;

class Double extends Cell implements CellContract
{
    public function __construct(
        public string $value,
        public string $addition,
        public ?int $maxLength = null,
        public bool $noTruncation = false,
    ) {
        parent::__construct($value, $maxLength, $noTruncation);
    }

    public function getType(): CellType
    {
        return CellType::DOUBLE;
    }
}
