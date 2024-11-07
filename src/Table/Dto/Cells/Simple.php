<?php

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Table\Dto\Interfaces\Cells\Cell as CellInterface;
use Patrikjak\Utils\Table\Dto\Interfaces\Cells\SupportsIcon;
use Patrikjak\Utils\Table\Enums\CellType;

class Simple extends Cell implements CellInterface, SupportsIcon
{
    public function __construct(public string $value, public ?Icon $icon = null)
    {
        parent::__construct($value);
    }

    public function getType(): CellType
    {
        return CellType::SIMPLE;
    }

    public function getIcon(): ?Icon
    {
        return $this->icon;
    }
}