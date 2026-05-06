<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Cells;

use Patrikjak\Utils\Table\Contracts\Cells\Cell as CellContract;
use Patrikjak\Utils\Table\Enums\Cells\CellType;

class Link extends Cell implements CellContract
{
    public function __construct(
        string $value,
        public string $href,
        public ?int $maxLength = null,
        public bool $noTruncation = false,
    ) {
        parent::__construct($value, $maxLength, $noTruncation);
    }

    public function getType(): CellType
    {
        return CellType::LINK;
    }
}
