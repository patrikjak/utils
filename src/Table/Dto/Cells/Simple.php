<?php

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Table\Dto\Interfaces\Cell as CellInterface;
use Patrikjak\Utils\Table\Enums\CellType;

class Simple extends Cell implements CellInterface
{
    public function __construct(public string $value)
    {
        parent::__construct($value);
    }

    public function getType(): CellType
    {
        return CellType::SIMPLE;
    }
}