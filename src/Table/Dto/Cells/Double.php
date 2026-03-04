<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Table\Enums\Cells\CellType;
use Patrikjak\Utils\Table\Interfaces\Cells\Cell as CellInterface;

class Double extends Cell implements CellInterface
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
