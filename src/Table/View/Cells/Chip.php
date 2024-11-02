<?php

namespace Patrikjak\Utils\Table\View\Cells;

use Closure;
use Illuminate\Contracts\View\View;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Exceptions\InvalidTypeException;
use Patrikjak\Utils\Table\Dto\Cells\Cell as AbstractCell;
use Patrikjak\Utils\Table\Dto\Cells\Chip as ChipCell;

final class Chip extends Cell
{
    public string $type;

    /** @throws InvalidTypeException */
    public function __construct(AbstractCell $cell, string $column)
    {
        parent::__construct($cell, $column);

        $this->type = $this->getType();
    }

    public function render(): View
    {
        return view('pjutils::table.cells.chip');
    }

    /** @throws InvalidTypeException */
    private function getType(): string
    {
        assert($this->cell instanceof ChipCell);

        $type = $this->cell->type;

        if (!$type instanceof Closure) {
            return $type->value;
        }

        $type = $type->call($this->cell);

        if (!$type instanceof Type) {
            throw new InvalidTypeException();
        }

        return $type->value;
    }
}