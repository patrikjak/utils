<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Pagination;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\Interfaces\Pagination\LinkItem;

class Paginator extends Component
{
    /** @var Collection<int, LinkItem> */
    public readonly Collection $links;

    public function __construct(public Settings $paginationSettings)
    {
        $this->links = $paginationSettings->links;
    }

    public function render(): View
    {
        return view('pjutils::table.pagination.paginator');
    }
}
