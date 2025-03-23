<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells;

use Illuminate\Contracts\View\View;
use Patrikjak\Utils\Table\Dto\Cells\Link as LinkCell;

final class Link extends Cell
{
    public string $link;

    public function render(): View
    {
        $this->link = $this->getLink();

        return view('pjutils::table.cells.link');
    }

    private function getLink(): string
    {
        assert($this->cell instanceof LinkCell);

        return $this->cell->href;
    }
}