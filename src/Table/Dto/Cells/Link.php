<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Table\Enums\Cells\CellType;
use Patrikjak\Utils\Table\Interfaces\Cells\Cell as CellInterface;

class Link extends Cell implements CellInterface
{
    public function __construct(string $value, public string $href)
    {
        parent::__construct($value);
    }

    public function getType(): CellType
    {
        return CellType::LINK;
    }
}