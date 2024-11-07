<?php

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Table\Dto\Interfaces\Cells\Cell as CellInterface;
use Patrikjak\Utils\Table\Enums\CellType;

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