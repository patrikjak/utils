<?php

namespace Patrikjak\Utils\Table\View\BulkActions;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\BulkActions\Item;

class Wrapper extends Component
{
    /**
     * @param array<Item> $bulkActions
     */
    public function __construct(public array $bulkActions)
    {
    }

    public function render(): View
    {
        return $this->view('pjutils::table.bulk-actions.wrapper');
    }
}
