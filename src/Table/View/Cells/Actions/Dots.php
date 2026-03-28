<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Cells\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;

class Dots extends Component
{
    /**
     * @param array<Item> $inlineActions
     * @param array<string> $hiddenInlineActionIds
     */
    public function __construct(
        public ?string $actionsDataAttributes = null,
        public array $inlineActions = [],
        public array $hiddenInlineActionIds = [],
        public bool $hasDropdownActions = false,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::table.cells.actions.dots');
    }
}
