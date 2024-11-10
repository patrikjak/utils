<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Table\Enums\CellType;
use Patrikjak\Utils\Table\Interfaces\Cells\Cell as CellInterface;

class Double extends Cell implements CellInterface
{
    public function __construct(public string $value, public string $addition)
    {
        parent::__construct($value);
    }

    public function getType(): CellType
    {
        return CellType::DOUBLE;
    }
}