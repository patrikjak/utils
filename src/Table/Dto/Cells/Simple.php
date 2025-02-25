<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Table\Enums\Cells\CellType;
use Patrikjak\Utils\Table\Interfaces\Cells\Cell as CellInterface;
use Patrikjak\Utils\Table\Interfaces\Cells\SupportsIcon;

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