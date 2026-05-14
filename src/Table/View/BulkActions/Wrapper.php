<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\BulkActions;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\ValueObjects\BulkActions\Item;

class Wrapper extends Component
{
    /**
     * @param Collection<int, Item> $bulkActions
     */
    public function __construct(public Collection $bulkActions)
    {
    }

    public function render(): View
    {
        return $this->view('pjutils::table.bulk-actions.wrapper');
    }
}
