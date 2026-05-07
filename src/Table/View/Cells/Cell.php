<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Cells;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Table\Contracts\Cells\SupportsIcon;
use Patrikjak\Utils\Table\ValueObjects\Cells\Cell as AbstractCell;

abstract class Cell extends Component
{
    public readonly string $cellClass;

    public readonly string $cellContent;

    public ?Icon $icon = null;

    abstract public function render(): View;

    public function __construct(
        public AbstractCell $cell,
        public string $column,
        public ?int $defaultMaxLength = null,
    ) {
        $this->cellClass = $this->getCellClass();
        $this->cellContent = $this->buildCellContent();

        if ($this->hasIcon()) {
            assert($this->cell instanceof SupportsIcon);

            $this->icon = $this->cell->getIcon();
        }
    }

    public function buildCellContent(): string
    {
        if ($this->cell->noTruncation) {
            return e($this->cell->value);
        }

        $effectiveMaxLength = $this->cell->maxLength ?? $this->defaultMaxLength;

        if ($effectiveMaxLength === null || mb_strlen($this->cell->value) <= $effectiveMaxLength) {
            return e($this->cell->value);
        }

        return sprintf(
            '<span class="truncated" data-tooltip="%s">%s&hellip;</span>',
            e($this->cell->value),
            e(mb_substr($this->cell->value, 0, $effectiveMaxLength)),
        );
    }

    public function getCellClass(): string
    {
        $classes = [$this->cell->getType()->value, $this->column];

        if ($this->hasIcon()) {
            $classes[] = 'with-icon';
        }

        return implode(' ', $classes);
    }

    protected function hasIcon(): bool
    {
        return $this->cell instanceof SupportsIcon && $this->cell->getIcon() !== null;
    }
}
