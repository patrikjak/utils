<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dtos\Pagination\Settings;

class PageSize extends Component
{
    /**
     * @var array<int, int>
     */
    public readonly array $pageSizeOptions;

    public readonly int $pageSize;

    public readonly string $path;

    public function __construct(Settings $paginationSettings)
    {
        $this->pageSizeOptions = $paginationSettings->pageSizeOptions;
        $this->pageSize = $paginationSettings->pageSize;
        $this->path = $paginationSettings->path;
    }

    public function render(): View
    {
        return view('pjutils::table.page-size');
    }
}
