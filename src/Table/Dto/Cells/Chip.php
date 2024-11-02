<?php

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Interfaces\Cell as CellInterface;
use Patrikjak\Utils\Table\Enums\CellType;

class Chip extends Cell implements CellInterface
{
    public function __construct(public string $value, public Type $type)
    {
        parent::__construct($value);
    }

    public function getType(): CellType
    {
        return CellType::CHIP;
    }
}