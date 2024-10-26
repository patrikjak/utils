<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Pagination extends Component
{
    /**
     * @var Collection<int, array{url: string, label:string, active: bool}>
     *
     */
    public readonly Collection $links;

    public function __construct(public Pagination $paginationSettings)
    {
        $this->links = $paginationSettings->links;
    }

    public function render(): View
    {
        return view('pjutils::components.table.pagination');
    }
}
