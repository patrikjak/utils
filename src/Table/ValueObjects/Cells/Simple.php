<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Cells;

use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Table\Contracts\Cells\Cell as CellContract;
use Patrikjak\Utils\Table\Contracts\Cells\SupportsIcon;
use Patrikjak\Utils\Table\Enums\Cells\CellType;

readonly class Simple extends Cell implements CellContract, SupportsIcon
{
    public function __construct(
        string $value,
        public ?Icon $icon = null,
        ?int $maxLength = null,
        bool $noTruncation = false,
    ) {
        parent::__construct($value, $maxLength, $noTruncation);
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
